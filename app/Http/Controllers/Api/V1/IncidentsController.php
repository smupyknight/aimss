<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Incident;
use App\Event;
use App\FormQuestion;
use App\FormAnswer;
use App\FormSubmission;
use Carbon\Carbon;
use Auth;
use DB;

class IncidentsController extends Controller
{

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'event_id'      => 'required',
			'incident_time' => 'required|date_format:' . Carbon::ISO8601,
		]);

		$event = Event::findOrFail($request->event_id);

		$time = Carbon::createFromFormat(Carbon::ISO8601, $request->incident_time, $event->timezone);

		$incident = new Incident;
		$incident->user_id = Auth::guard('api')->user()->id;
		$incident->event_id = $request->event_id;
		$incident->name = $event->name . ' ' . $time->format('d/m/Y');
		$incident->incident_time = $time->setTimezone('UTC');
		$incident->save();

		return response()->json(['id' => $incident->id]);
	}

	public function getList($event_id)
	{
		$incidents = Incident::where('event_id', $event_id)->orderBy('incident_time', 'desc')->get();

		$results = [];

		foreach ($incidents as $incident) {
			$results[] = [
				'id'            => $incident->id,
				'name'          => $incident->name,
				'event_name'    => $incident->event->name,
				'incident_time' => $incident->incident_time->setTimezone($incident->event->timezone)->format('c'),
				'created_at'    => $incident->created_at->setTimezone($this->user->timezone)->format('c'),
				'updated_at'    => $incident->updated_at->setTimezone($this->user->timezone)->format('c'),
			];
		}

		return response()->json($results);
	}

	public function getForm()
	{
		$query = DB::table('form_questions AS q')
					->select('q.*', 'c.name AS category_name')
					->join('form_categories AS c', 'q.category_id', '=', 'c.id')
					->whereNull('q.deleted_at')
					->orderBy('c.num', 'asc')
					->orderBy('q.num', 'asc');

		$user = Auth::guard('api')->user();

		switch ($user->type) {
			case 'Spectator':
				$query = $query->where('q.show_to_spectator', '!=', 'No');
				break;
			case 'Crew':
				$query = $query->where('q.show_to_crew', '!=', 'No');
				break;
			case 'Medical':
				$query = $query->where('q.show_to_medical', '!=', 'No');
				break;
			case 'Organizer':
				$query = $query->where('q.show_to_organiser', '!=', 'No');
				break;
			case 'Scrutineer':
				$query = $query->where('q.show_to_scrutineer', '!=', 'No');
				break;
		}

		$questions = $query->get();

		$form_data = [];

		foreach ($questions as $question) {
			if (!isset($form_data[$question->category_id])) {
				$form_data[$question->category_id] = [
					'id'        => $question->category_id,
					'name'      => $question->category_name,
					'questions' => [],
				];
			}

			$validation_type = '';

			switch ($user->type) {
				case 'Spectator':
					$validation_type = $question->show_to_spectator;
					break;
				case 'Crew':
					$validation_type = $question->show_to_crew;
					break;
				case 'Medical':
					$validation_type = $question->show_to_medical;
					break;
				case 'Organizer':
					$validation_type = $question->show_to_organiser;
					break;
				case 'Scrutineer':
					$validation_type = $question->show_to_scrutineer;
					break;
			}

			$form_data[$question->category_id]['questions'][] = [
				'id'                          => $question->id,
				'question'                    => $question->question,
				'conditional_question_id'     => $question->conditional_question_id,
				'conditional_question_answer' => $question->conditional_question_answer,
				'reference_image'             => $question->reference_image ? url('question-reference-images') . '/' . $question->reference_image : '',
				'type'                        => $question->type,
				'validation_type'             => $validation_type,
				'options'                     => $question->options,
			];
		}

		return response()->json(array_values($form_data));
	}

	public function postForm(\App\Http\Requests\SubmitAnswers $request)
	{
		$incident = Incident::where('status', 'open')->findOrFail($request->incident_id);

		$user = Auth::guard('api')->user();

		$submission = FormSubmission::firstOrCreate([
			'incident_id' => $incident->id,
			'user_id'     => $user->id,
		]);

		$submission->answers()->delete();

		$answers = collect($request->get('answers'));

		foreach (FormQuestion::all() as $question) {
			$question_answers = (array) $answers->get($question->id);

			$submission->answers()->create([
				'question_id' => $question->id,
				'answers'     => json_encode(array_filter($question_answers)),
			]);
		}

		return response()->json(['submission_id' => $submission->id]);
	}

}

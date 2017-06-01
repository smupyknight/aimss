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

class SubmissionsController extends Controller
{

	/**
	 * Returns a list of the user's submissions.
	 */
	public function getIndex()
	{
		$submissions = FormSubmission::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();

		$results = [];

		foreach ($submissions as $submission) {
			$results[] = [
				'id'          => $submission->id,
				'incident_id' => $submission->incident_id,
				'event_id'    => $submission->incident->event_id,
				'name'        => $submission->incident->event->name . ' on '. $submission->incident->incident_time->setTimezone($this->user->timezone)->format('l, j M \a\t g:ia'),
				'created_at'  => $submission->created_at->setTimezone($this->user->timezone)->format('c'),
				'updated_at'  => $submission->updated_at->setTimezone($this->user->timezone)->format('c'),
			];
		}

		return response()->json($results);
	}

	/**
	 * Returns a submission's details.
	 */
	public function getView($submission_id)
	{
		$submission = FormSubmission::where('user_id', $this->user->id)->findOrFail($submission_id);

		$questions = DB::table('form_answers AS a')
			->join('form_questions AS q', 'a.question_id', '=', 'q.id')
			->join('form_categories AS c', 'q.category_id', '=', 'c.id')
			->where('a.submission_id', $submission->id)
			->orderBy('c.num', 'asc')
			->orderBy('q.num', 'asc')
			->select([
				'c.name AS category',
				'q.question',
				'a.answers',
			])
			->get();

		$response = [
			'id'          => $submission->id,
			'incident_id' => $submission->incident_id,
			'event_id'    => $submission->incident->event_id,
			'name'        => $submission->incident->event->name . ' on '. $submission->incident->incident_time->setTimezone($this->user->timezone)->format('l, j M \a\t g:ia'),
			'created_at'  => $submission->created_at->setTimezone($this->user->timezone)->format('c'),
			'updated_at'  => $submission->updated_at->setTimezone($this->user->timezone)->format('c'),
			'categories'  => [],
		];

		foreach ($questions as $question) {
			$response['categories'][$question->category][$question->question] = json_decode($question->answers);
		}

		return response()->json($response);
	}

}

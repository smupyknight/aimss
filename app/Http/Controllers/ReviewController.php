<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormAnswer;
use App\FormCategory;
use App\FormQuestion;
use App\FormSubmission;
use App\Incident;
use Carbon\Carbon;
use DB;

class ReviewController extends Controller
{

	/**
	 * Displays the incidents that are ready for review.
	 */
	public function getIndex()
	{
		$incidents = Incident::join('events AS e', 'incidents.event_id', '=', 'e.id')
			->join('form_submissions AS s', function($join) {
				$join->on('s.incident_id', '=', 'incidents.id')
					->where('s.is_aggregate', 0);
			})
			->where('incidents.status', '!=', 'complete')
			->where('incidents.incident_time', '<=', new Carbon('7 days ago'))
			->groupBy('incidents.id')
			->orderBy('incidents.incident_time', 'asc')
			->select([
				'incidents.*',
				'e.name AS event_name',
				DB::raw('COUNT(s.id) AS num_reports'),
				DB::raw('MAX(s.created_at) AS last_report_time'),
			])
			->paginate(25);

		return view('pages.review-list')
			->with('incidents', $incidents);
	}

	/**
	 * Returns the modal for starting a review.
	 */
	public function getStart($incident_id)
	{
		$incident = Incident::findOrFail($incident_id);

		return view('modals.review-start')
			->with('incident', $incident);
	}

	/**
	 * Starts the review for an incident.
	 */
	public function postStart($incident_id)
	{
		$incident = Incident::findOrFail($incident_id);

		$incident->update([
			'status' => 'review',
		]);
	}

	/**
	 * Displays the review screen for an incident.
	 */
	public function getDo($incident_id)
	{
		$incident = Incident::findOrFail($incident_id);

		$categories = FormCategory::orderBy('num', 'asc')->get();

		$answers = DB::table('form_answers AS a')
			->join('form_submissions AS s', 'a.submission_id', '=', 's.id')
			->where('s.incident_id', $incident->id)
			->where('s.is_aggregate', 0)
			->select(['a.question_id', 'a.answers'])
			->get()
			->map(function($answer) {
				$answer->answers = json_decode($answer->answers);
				return $answer;
			})
			->groupBy('question_id');

		$defaults = $this->determineDefaults($categories, $incident, $answers);

		return view('pages.review-do')
			->with('incident', $incident)
			->with('categories', $categories)
			->with('answers', $answers)
			->with('defaults', $defaults);
	}

	/**
	 * Determines the default values that will be used to populate the review
	 * form.
	 *
	 * If the aggregate submission already has an answer then it will be used,
	 * else if all the given answers are the same then they will be used,
	 * else the default will be blank.
	 */
	public function determineDefaults($categories, Incident $incident, $answers)
	{
		$defaults = [];

		$aggregate = $incident->formSubmissions()->where('is_aggregate', 1)->first();

		if ($aggregate) {
			$defaults = DB::table('form_answers')
				->where('submission_id', $aggregate->id)
				->select(['question_id', 'answers'])
				->get()
				->groupBy('question_id')
				->map(function($thing) {
					return json_decode($thing[0]->answers);
				})
				->all();
		}

		foreach ($categories as $category) {
			foreach ($category->questions as $question) {
				if (isset($defaults[$question->id])) {
					continue;
				}

				if (!isset($answers[$question->id])) {
					$defaults[$question->id] = [''];
					continue;
				}

				$is_unanimous = $answers[$question->id]->unique()->count() == 1;
				$defaults[$question->id] = $is_unanimous ? $answers[$question->id][0]->answers : [''];
			}
		}

		return $defaults;
	}

	/**
	 * Handles saving a review.
	 *
	 * The aggregate submission is created if it doesn't exist yet, and its
	 * answers are set from the posted values.
	 */
	public function postDo(Request $request, $incident_id)
	{
		$incident = Incident::findOrFail($incident_id);

		// Retrieve or create the FormSubmission
		$submission = FormSubmission::firstOrNew([
			'incident_id'  => $incident->id,
			'is_aggregate' => 1,
		]);

		if (!$submission->exists) {
			$submission->user_id = $this->user->id;
			$submission->save();
		}

		// Iterate the questions and set the answers
		foreach (FormQuestion::all() as $question) {
			if (isset($request->answers[$question->id])) {
				$answers = $request->answers[$question->id];
			} else {
				$answers = [''];
			}

			$submission->answers()->updateOrCreate(
				['question_id' => $question->id],
				['answers' => json_encode($answers)]
			);
		}

		$this->mergeImageAnswers($submission);

		// If finishing, do validation and mark the incident as complete
		if ($request->action == 'finish') {
			$errors = $submission->validate();

			if ($errors) {
				return redirect()->back()->withErrors($errors);
			}

			$incident->status = 'complete';
			$incident->save();

			return redirect('/review');
		}

		return redirect()->back();
	}

	/**
	 * Merges all image answers into the aggregate submission.
	 *
	 * Unlike most answers where the admin picks each one to use or enters their
	 * own, all images provided in the end user submissions are used in the
	 * aggregate.
	 */
	private function mergeImageAnswers(FormSubmission $aggregate)
	{
		$image_answers = DB::table('form_submissions AS s')
			->join('form_answers AS a', 'a.submission_id', '=', 's.id')
			->join('form_questions AS q', 'a.question_id', '=', 'q.id')
			->where('s.incident_id', $aggregate->incident_id)
			->where('s.is_aggregate', 0)
			->where('q.type', 'image')
			->select(['a.question_id', 'a.answers'])
			->get();

		$merged = [];

		foreach ($image_answers as $answer) {
			if (!isset($merged[$answer->question_id])) {
				$merged[$answer->question_id] = [];
			}

			$merged[$answer->question_id] = array_merge($merged[$answer->question_id], json_decode($answer->answers));
		}

		foreach ($merged as $question_id => $answers) {
			$aggregate->answers()->updateOrCreate(
				['question_id' => $question_id],
				['answers' => json_encode($answers)]
			);
		}
	}

}

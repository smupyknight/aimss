<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\FormQuestion;

class FormSubmission extends Model
{

	protected $guarded = [];

	public function validate()
	{
		$submitter = $this->user;

		switch ($submitter->type) {
			case 'spectator':
				$show_to_field = 'show_to_spectator';
				break;
			case 'crew':
				$show_to_field = 'show_to_crew';
				break;
			case 'medical':
				$show_to_field = 'show_to_medical';
				break;
			case 'organiser':
				$show_to_field = 'show_to_organizer';
				break;
			case 'scrutineer':
				$show_to_field = 'show_to_scrutineer';
				break;
			default:
				$show_to_field = null;
		}

		$errors = [];

		$all_answers = DB::table('form_answers')
			->where('submission_id', $this->id)
			->select(['question_id', 'answers'])
			->pluck('answers', 'question_id')
			->map(function ($answers) {
				return json_decode($answers);
			})
			->all();

		foreach (FormQuestion::all() as $question) {
			// Is it dependent on another question?
			if ($question->conditional_question_id) {
				if ($all_answers[$question->conditional_question_id][0] != $question->conditional_question_answer) {
					continue;
				}
			}

			$answers = $all_answers[$question->id];

			// Is it required for this user type?
			if ($show_to_field && $question->$show_to_field == 'required' && $question->type != 'boolean-null') {
				if (!count($answers)) {
					$errors[] = 'Question #' . $question->num . ' is required.';
				}
			}

			if (!count($answers)) {
				continue;
			}

			// If it's checkbox or select, is the answer in the option list?
			if ($question->type == 'select' || $question->type == 'checkboxes') {
				$options = explode("\r\n", $question->options);

				foreach ($answers as $answer) {
					if (!in_array($answer, $options)) {
						$errors[] = 'Answer "' . $answer . '" for question #' . $question->num . ' is not a valid answer.';
					}
				}
			}

			// If it's boolean, check it's yes or no
			if ($question->type == 'boolean') {
				if ($answers[0] != 'Yes' && $answers[0] != 'No') {
					$errors[] = 'Answer "' . $answers[0] . '" for question #' . $question->num . ' is not a valid answer.';
				}
			}

			// If it's boolean-null, check it's yes, no or empty
			if ($question->type == 'boolean-null') {
				if ($answers[0] != 'Yes' && $answers[0] != 'No' && $answers[0] != '') {
					$errors[] = 'Answer "' . $answers[0] . '" for question #' . $question->num . ' is not a valid answer.';
				}
			}
		}

		return $errors;
	}

	public function answers()
	{
		return $this->hasMany('App\FormAnswer', 'submission_id');
	}

	public function incident()
	{
		return $this->belongsTo('App\Incident');
	}

	public function injuries()
	{
		return $this->hasMany('App\Injury', 'submission_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function damages()
	{
		return $this->hasMany('App\Damage', 'submission_id');
	}

	public function media()
	{
		return $this->hasMany('App\SubmissionMedia', 'submission_id');
	}

}

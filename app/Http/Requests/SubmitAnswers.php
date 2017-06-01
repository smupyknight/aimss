<?php

namespace App\Http\Requests;

use App\FormQuestion;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswers extends FormRequest
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'answers'     => 'required|array',
			'incident_id' => 'required',
		];
	}

	/**
	 * Adds an 'after' validation hook to do the actual validation.
	 */
	protected function getValidatorInstance()
	{
		return parent::getValidatorInstance()->after(function($validator) {
			$answers = collect($this->get('answers'));

			foreach (FormQuestion::all() as $question) {
				$question_answers = (array) $answers->get($question->id);

				$this->validateRequired($validator, $question, $question_answers);
				$this->validateOptions($validator, $question, $question_answers);
				$this->validateDatetime($validator, $question, $question_answers);
			}
		});
	}

	/**
	 * Fields are generally required if the show_to field is set to 'required'.
	 * However, if this question is dependent on another question, and the
	 * conditional criteria was not met, then this question wasn't shown to the
	 * user and therefore isn't required.
	 *
	 * If the question type is boolean null, an empty answer is the N/A option,
	 * so this question type is never required.
	 */
	private function validateRequired($validator, FormQuestion $question, array $answers)
	{
		if ($answers) {
			return;
		}

		if ($question->type == 'boolean-null') {
			return;
		}

		switch ($this->user()->type) {
			case 'Spectator':  $show_to_field = 'show_to_spectator'; break;
			case 'Crew':       $show_to_field = 'show_to_crew'; break;
			case 'Medical':    $show_to_field = 'show_to_medical'; break;
			case 'Organiser':  $show_to_field = 'show_to_organizer'; break;
			case 'Scrutineer': $show_to_field = 'show_to_scrutineer'; break;
			default:
				throw new Exception('Unknown user type "' . $this->user()->type . '"');
		}

		if ($question->$show_to_field != 'required') {
			return;
		}

		if ($question->conditional_question_id && $this->get("answers.{$question->conditional_question_id}.0") != $question->conditional_question_answer) {
			return;
		}

		$validator->errors()->add("answers[{$question->id}]", 'This field is required.');
	}

	/**
	 * For checkboxes and selects, validates that the given answers are in the
	 * question's options list.
	 */
	private function validateOptions($validator, FormQuestion $question, array $answers)
	{
		if ($question->type != 'checkboxes' && $question->type != 'select') {
			return;
		}

		$options = explode("\r\n", $question->options);

		if (array_diff($answers, $options)) {
			$validator->errors()->add("answers[{$question->id}]", 'Please select a valid option.');
		}
	}

	/**
	 * Validates that the provided answer is in the correct date format.
	 */
	private function validateDatetime($validator, FormQuestion $question, array $answers)
	{
		if ($question->type != 'datetime') {
			return;
		}

		if (!$answers) {
			return;
		}

		if (!DateTime::createFromFormat(DateTime::ISO8601, $answers[0])) {
			$validator->errors()->add("answers[{$question->id}]", 'The date format must be ISO 8601.');
		}
	}

}

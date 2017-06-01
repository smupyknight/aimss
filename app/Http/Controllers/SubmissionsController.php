<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormCategory;
use App\FormSubmission;

class SubmissionsController extends Controller
{

	public function getView($submission_id)
	{
		$submission = FormSubmission::findOrFail($submission_id);

		$answers = $submission->answers()->pluck('answers', 'question_id')
			->map(function($answer) { return json_decode($answer); });

		$categories = FormCategory::orderBy('num', 'asc')->get();

		return view('pages.submissions-view')
			->with('submission', $submission)
			->with('answers', $answers)
			->with('categories', $categories);
	}

}

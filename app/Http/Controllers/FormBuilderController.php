<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ServiceValidationException;
use App\FormCategory;
use App\FormQuestion;
use Auth;
use DB;

class FormBuilderController extends Controller
{

	/**
	 * Show list of all questions
	 */
	public function getIndex()
	{
		$categories = FormCategory::orderBy('num', 'asc')->get();

		return view('pages.formbuilder-index')
		     ->with('title', 'Form Builder')
		     ->with('categories', $categories);
	}

	public function postCreateCategory(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
		]);

		$after_category = FormCategory::find($request->after);

		$num = $after_category ? $after_category->num + 1 : 1;

		FormCategory::where('num', '>=', $num)->update([
			'num' => DB::raw('num + 1'),
		]);

		FormCategory::create([
			'num'  => $num,
			'name' => $request->name,
		]);
	}

	public function postCreateQuestion(Request $request)
	{
		$this->validate($request, [
			'category_id'             => 'required',
			'conditional_question_id' => 'exists:form_questions,id,category_id,' . $request->category_id,
			'question'                => 'required',
			'type'                    => 'required|in:shorttext,longtext,boolean,boolean-null,checkboxes,select,datetime,image',
			'options'                 => 'required_if:type,checkboxes,select',
			'reference_image'         => 'mimes:jpeg,png',
			'show_to_spectator'       => 'required|in:required,optional,no',
			'show_to_crew'            => 'required|in:required,optional,no',
			'show_to_medical'         => 'required|in:required,optional,no',
			'show_to_organiser'       => 'required|in:required,optional,no',
			'show_to_scrutineer'      => 'required|in:required,optional,no',
		], [
			'show_to_spectator.required'  => 'Please select an availability for spectator users.',
			'show_to_crew.required'       => 'Please select an availability for crew users.',
			'show_to_medical.required'    => 'Please select an availability for medical users.',
			'show_to_organiser.required'  => 'Please select an availability for organiser users.',
			'show_to_scrutineer.required' => 'Please select an availability for scrutineer users.',
		]);

		$after_question = FormQuestion::find($request->after);

		$num = $after_question ? $after_question->num + 1 : 1;

		FormQuestion::whereCategoryId($request->category_id)->where('num', '>=', $num)->update([
			'num' => DB::raw('num + 1'),
		]);

		$question = FormQuestion::create([
			'category_id'                 => $request->category_id,
			'conditional_question_id'     => $request->conditional_question_id ? $request->conditional_question_id : null,
			'created_by'                  => Auth::user()->id,
			'num'                         => $num,
			'question'                    => $request->question,
			'type'                        => $request->type,
			'options'                     => $request->options,
			'conditional_question_answer' => $request->conditional_question_answer,
			'show_to_spectator'           => $request->show_to_spectator,
			'show_to_crew'                => $request->show_to_crew,
			'show_to_medical'             => $request->show_to_medical,
			'show_to_organiser'           => $request->show_to_organiser,
			'show_to_scrutineer'          => $request->show_to_scrutineer,
		]);

		if ($request->file('reference_image')) {
			$question->setReferenceImage($request->file('reference_image'));
		}
	}

	public function postEditCategory(Request $request, $category_id)
	{
		$this->validate($request, [
			'name' => 'required',
		]);

		$category = FormCategory::findOrFail($category_id);

		$after_category = FormCategory::find($request->after);

		$num = $after_category ? $after_category->num + 1 : 1;

		// If we've moved the position of the category, increment the numbers of
		// everything after the new spot. This ensures the new spot is vacant.
		if ($num != $category->num) {
			FormCategory::where('num', '>=', $num)->update([
				'num' => DB::raw('num + 1'),
			]);
		}

		$category->num = $num;
		$category->name = $request->name;
		$category->save();

		// Re-index the "num" fields
		DB::statement('SET @num := 0');
		DB::statement('UPDATE form_categories SET num = (@num := @num + 1) ORDER BY num ASC');
	}

	public function postEditQuestion(Request $request, $question_id)
	{
		$this->validate($request, [
			'category_id'             => 'required',
			'conditional_question_id' => 'exists:form_questions,id,category_id,' . $request->category_id,
			'question'                => 'required',
			'type'                    => 'required|in:shorttext,longtext,boolean,boolean-null,checkboxes,select,datetime,image',
			'options'                 => 'required_if:type,checkboxes,select',
			'reference_image'         => 'mimes:jpeg,png',
			'show_to_spectator'       => 'required|in:required,optional,no',
			'show_to_crew'            => 'required|in:required,optional,no',
			'show_to_medical'         => 'required|in:required,optional,no',
			'show_to_organiser'       => 'required|in:required,optional,no',
			'show_to_scrutineer'      => 'required|in:required,optional,no',
		], [
			'show_to_spectator.required'  => 'Please select an availability for spectator users.',
			'show_to_crew.required'       => 'Please select an availability for crew users.',
			'show_to_medical.required'    => 'Please select an availability for medical users.',
			'show_to_organiser.required'  => 'Please select an availability for organiser users.',
			'show_to_scrutineer.required' => 'Please select an availability for scrutineer users.',
		]);

		$question = FormQuestion::findOrFail($question_id);
		$prev_category_id = $question->category_id;

		$after_question = FormQuestion::find($request->after);

		$num = $after_question ? $after_question->num + 1 : 1;

		// Increment the numbers of everything after the new spot. This ensures
		// the new spot is vacant.
		FormQuestion::whereCategoryId($request->category_id)->where('num', '>=', $num)->update([
			'num' => DB::raw('num + 1'),
		]);

		$question->category_id = $request->category_id;
		$question->conditional_question_id = $request->conditional_question_id ? $request->conditional_question_id : null;
		$question->num = $num;
		$question->question = $request->question;
		$question->type = $request->type;
		$question->options = $request->options;
		$question->conditional_question_answer = $request->conditional_question_answer;
		$question->show_to_spectator = $request->show_to_spectator;
		$question->show_to_crew = $request->show_to_crew;
		$question->show_to_medical = $request->show_to_medical;
		$question->show_to_organiser = $request->show_to_organiser;
		$question->show_to_scrutineer = $request->show_to_scrutineer;
		$question->save();

		// Re-index the "num" fields for both the previous and new categories
		DB::statement('SET @num := 0');
		DB::statement('UPDATE form_questions SET num = (@num := @num + 1) WHERE category_id = ? AND deleted_at IS NULL ORDER BY num ASC', [$prev_category_id]);

		DB::statement('SET @num := 0');
		DB::statement('UPDATE form_questions SET num = (@num := @num + 1) WHERE category_id = ? AND deleted_at IS NULL ORDER BY num ASC', [$request->category_id]);

		if ($request->file('reference_image')) {
			$question->setReferenceImage($request->file('reference_image'));
		} elseif ($request->remove_reference_image) {
			$question->removeReferenceImage();
		}
	}

	public function postDeleteCategory($category_id)
	{
		$category = FormCategory::findOrFail($category_id);

		if (count($category->questions)) {
			throw new ServiceValidationException('You cannot delete a category if it contains questions.');
		}

		$category->delete();

		// Re-index the "num" fields
		DB::statement('SET @num := 0');
		DB::statement('UPDATE form_categories SET num = (@num := @num + 1) ORDER BY num ASC');
	}

	public function postDeleteQuestion($question_id)
	{
		$question = FormQuestion::findOrFail($question_id);
		$question->delete();

		// Re-index the "num" fields
		DB::statement('SET @num := 0');
		DB::statement('UPDATE form_questions SET num = (@num := @num + 1) WHERE deleted_at IS NULL ORDER BY num ASC');
	}

	public function getQuestions($category_id)
	{
		$questions = FormQuestion::whereCategoryId($category_id)->orderBy('num', 'asc')->get(['id', 'num', 'question']);

		return response()->json($questions);
	}

}

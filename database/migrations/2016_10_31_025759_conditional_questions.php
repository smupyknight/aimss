<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConditionalQuestions extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('form_questions', function(Blueprint $table) {
			$table->integer('conditional_question_id')->unsigned()->nullable()->after('category_id');
			$table->string('conditional_question_answer')->after('reference_image');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('form_questions', function(Blueprint $table) {
			$table->dropColumn('conditional_question_id');
			$table->dropColumn('conditional_question_answer');
		});
	}

}

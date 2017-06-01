<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentsAndSubmissionTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('incidents', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->datetime('incident_time');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		 });

		Schema::create('form_submissions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('incident_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('form_answers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('submission_id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->text('answers');
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('submission_id')->references('id')->on('form_submissions')->onDelete('cascade');
			$table->foreign('question_id')->references('id')->on('form_questions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incidents');
		Schema::drop('form_submissions');
		Schema::drop('form_answers');
	}

}

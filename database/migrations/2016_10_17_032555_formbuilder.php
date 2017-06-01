<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Formbuilder extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_categories', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('num');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('form_questions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->unsigned()->index();
			$table->integer('created_by')->unsigned();
			$table->string('question');
			$table->enum('type', ['boolean','boolean-null','checkboxes','select','shorttext','longtext','datetime','image']);
			$table->text('options');
			$table->integer('num');
			$table->enum('show_to_general', ['no','optional','required']);
			$table->enum('show_to_crew', ['no','optional','required']);
			$table->enum('show_to_medical', ['no','optional','required']);
			$table->enum('show_to_organiser', ['no','optional','required']);
			$table->datetime('created_at');
			$table->datetime('updated_at');
			$table->datetime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('form_categories');
		Schema::drop('form_questions');
	}

}

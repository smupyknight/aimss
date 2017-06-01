<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionMediaTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submission_media', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('submission_id')->unsigned()->references('id')->on('form_submissions');
			$table->string('filename', 20);
			$table->enum('type', ['Image', 'Video']);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('submission_media');
	}

}

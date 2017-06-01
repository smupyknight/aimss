<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamageTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('damage', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('submission_id')->unsigned()->references('id')->on('form_submissions');
			$table->enum('type', ['Left', 'Right', 'Top', 'Back', 'Front']);
			$table->string('filename');
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
		Schema::drop('damage');
	}

}

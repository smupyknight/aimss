<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFormQuestionsGeneralToSpectator extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Rename column `show_to_general` to `show_to_spectator`
		DB::statement("ALTER TABLE form_questions CHANGE COLUMN show_to_general show_to_spectator enum('no','optional','required') NOT NULL");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Rename column `show_to_spectator` back to `show_to_general`
		DB::statement("ALTER TABLE form_questions CHANGE COLUMN show_to_spectator show_to_general enum('no','optional','required') NOT NULL");
	}

}

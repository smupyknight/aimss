<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScrutineerOptions extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('form_questions', function (Blueprint $table) {
			$table->enum('show_to_scrutineer', ['no','optional','required'])->after('show_to_organiser');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('form_questions', function (Blueprint $table) {
			$table->dropColumn('show_to_scrutineer');
		});
	}

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncidentChanges extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('incidents', function (Blueprint $table) {
			$table->string('name')->after('event_id');
			$table->enum('status', ['open', 'review', 'complete'])->after('name');
		});

		Schema::table('form_submissions', function (Blueprint $table) {
			$table->tinyInteger('is_aggregate')->after('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('incidents', function (Blueprint $table) {
			$table->dropColumn('name');
			$table->dropColumn('status');
		});

		Schema::table('form_submissions', function (Blueprint $table) {
			$table->dropColumn('is_aggregate');
		});
	}

}

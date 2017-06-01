<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEventFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->dropColumn('notes');
			$table->dropColumn('light_conditions');
			$table->dropColumn('weather');
			$table->dropColumn('interruption');
		});

		Schema::table('event_stages', function (Blueprint $table) {
			$table->decimal('fastest_time', 10, 4)->unsigned()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->string('notes')->after('competition_service');
			$table->string('light_conditions')->after('notes');
			$table->string('weather')->after('light_conditions');
			$table->string('interruption')->after('competitor_number');
		});

		Schema::table('event_stages', function (Blueprint $table) {
			$table->decimal('fastest_time', 4, 4)->change();
		});
	}

}

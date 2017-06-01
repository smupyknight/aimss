<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventStatus extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->renameColumn('event_name', 'name');
			$table->renameColumn('event_status', 'type');
			$table->renameColumn('event_style', 'style');
		});

		Schema::table('events', function (Blueprint $table) {
			$table->enum('status', ['pending', 'accepted'])->after('name');
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
			$table->renameColumn('name', 'event_name');
			$table->renameColumn('type', 'event_status');
			$table->renameColumn('style', 'event_style');

			$table->dropColumn('status');
		});
	}

}

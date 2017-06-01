<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInEventTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function (Blueprint $table) {
			$table->string('location')->after('interruption');
			$table->string('description', 255)->after('location');
			$table->dateTime('start_date')->after('description');
			$table->dateTime('end_date')->after('start_date');
			$table->string('timezone', 100)->after('end_date');
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
			$table->dropColumn('location');
			$table->dropColumn('description');
			$table->dropColumn('start_date');
			$table->dropColumn('end_date');
			$table->dropColumn('timezone');
		});
	}

}

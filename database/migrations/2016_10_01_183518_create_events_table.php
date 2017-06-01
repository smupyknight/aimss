<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->string('event_name');
			$table->string('event_status');
			$table->string('event_style');
			$table->string('competition_service')->nullable();
			$table->string('notes')->nullable();
			$table->string('light_conditions')->nullable();
			$table->string('weather')->nullable();
			$table->string('competitor_progression')->nullable();
			$table->string('competitor_progression_other')->nullable();
			$table->string('medical_park_firstaid')->nullable();
			$table->string('medical_park_ambulance')->nullable();
			$table->string('medical_park_other')->nullable();
			$table->string('medical_route_firstaid')->nullable();
			$table->string('medical_route_ambulance')->nullable();
			$table->string('medical_route_other')->nullable();
			$table->string('spectator')->nullable();
			$table->integer('competitive_distance')->nullable();
			$table->integer('competitor_number')->nullable();
			$table->string('interruption')->nullable();
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('user_id')->references('id')->on('users');
		 });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}

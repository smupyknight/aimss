<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTypeGeneralToSpectator extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Change General to Spectator
		DB::statement("ALTER TABLE users MODIFY `type` ENUM('Admin','Scrutineer','Organizer','Crew','Medical','Spectator')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Change Spectator back to General
		DB::statement("ALTER TABLE users MODIFY `type` ENUM('Admin','Scrutineer','Organizer','Crew','Medical','General')");
	}

}

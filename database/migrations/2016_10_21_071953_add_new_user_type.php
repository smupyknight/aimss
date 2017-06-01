<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewUserType extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE users MODIFY type ENUM('Admin','Scrutineer','Organizer','Crew','Medical','General')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 DB::statement("ALTER TABLE users MODIFY type ENUM('Admin','Organizer','Crew','Medical','General')");
	}

}

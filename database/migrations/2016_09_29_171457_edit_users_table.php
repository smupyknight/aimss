<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('name');
		});

		Schema::table('users', function (Blueprint $table) {
			$table->string('first_name', 255)->after('id');
			$table->string('last_name', 255)->after('first_name')->nullable();
			$table->string('phone', 20)->after('last_name')->nullable();
			$table->enum('type', ['General','Crew','Medical','Organizer','Admin'])->after('password')->nullable();
			$table->string('identification_type', 250)->after('type')->nullable();
			$table->string('identification_number', 250)->after('identification_type')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('first_name');
			$table->dropColumn('last_name');
			$table->dropColumn('phone');
			$table->dropColumn('type');
			$table->dropColumn('identification_type');
			$table->dropColumn('identification_number');

			$table->string('name', 60)->after('id');
		});
	}

}

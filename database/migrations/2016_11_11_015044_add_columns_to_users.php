<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsers extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->text('address_1')->after('identification_number');
			$table->text('address_2')->after('address_1');
			$table->string('suburb')->after('address_2');
			$table->string('postcode')->after('suburb');
			$table->string('state')->after('postcode');
			$table->string('timezone')->after('state');
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
			$table->dropColumn('address_1');
			$table->dropColumn('address_2');
			$table->dropColumn('suburb');
			$table->dropColumn('postcode');
			$table->dropColumn('state');
			$table->dropColumn('timezone');
		});
	}

}

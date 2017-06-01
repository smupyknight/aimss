<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveIdentificationFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_images', function (Blueprint $table) {
			$table->string('type')->after('file');
			$table->string('identification_number')->after('type');
		});

		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('identification_type');
			$table->dropColumn('identification_number');
			$table->enum('status', ['pending','invited','active','disabled'])->after('type');
		});

		DB::statement("UPDATE users SET status = 'active'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_images', function (Blueprint $table) {
			$table->dropColumn('type');
			$table->dropColumn('identification_number');
		});

		Schema::table('users', function (Blueprint $table) {
			$table->string('identification_type', 250)->after('is_subscribed');
			$table->string('identification_number', 250)->after('identification_type');
			$table->dropColumn('status');
		});
	}

}

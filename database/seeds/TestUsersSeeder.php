<?php

use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
	// For testing purpose

	public function run()
	{
		// Create test admin, user
		$admin = factory(App\User::class)->create([
			'type'     => 'Admin',
			'email'    => 'testadmin@com.com',
			'password' => bcrypt('tester'),
		]);

		// Spectator user
		$spectator = factory(App\User::class)->create([
			'email'    => 'testuser@com.com',
			'password' => bcrypt('tester'),
		]);

		// Crew
		$crew = factory(App\User::class)->create([
			'type'     => 'Crew',
			'email'    => 'testcrew@com.com',
			'password' => bcrypt('tester'),
		]);

		// Medical
		$medical = factory(App\User::class)->create([
			'type'     => 'Medical',
			'email'    => 'testmedical@com.com',
			'password' => bcrypt('tester'),
		]);

		// Organizer
		$organizer = factory(App\User::class)->create([
			'type'     => 'Organizer',
			'email'    => 'testorganizer@com.com',
			'password' => bcrypt('tester'),
		]);

		// Scrutineer
		$scrutineer = factory(App\User::class)->create([
			'type'     => 'Scrutineer',
			'email'    => 'testscrutineer@com.com',
			'password' => bcrypt('tester'),
		]);
	}

}

<?php

class ApiV1AccountControllerTest extends TestCase
{
	// Api/V1/AccountController Test

	/**
	 * Test getIndex
	 * GET: /api/v1/account
	 */
	public function testGetIndex()
	{
		// Create a test user
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$this->actingAs($user)
			->get('/api/v1/account?api_token=' . $api_token)
			->assertResponseStatus(200)
			->seeJsonSubset([
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'email'      => $user->email,
				'address_1'  => $user->address_1,
				'address_2'  => $user->address_2,
				'suburb'     => $user->suburb,
				'postcode'   => $user->postcode,
				'state'      => $user->state,
				'phone'      => $user->phone,
				'type'       => $user->type,
			]);
	}

	/**
	 * Test postEdit
	 * POST: /api/v1/account/edit
	 */
	public function testPostEdit()
	{
		// Create a test user.
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Test post edit.
		$this->actingAs($user)
			->post('/api/v1/account/edit', [
				'api_token'             => $api_token,
				'first_name'            => 'TestNewFirstName',
				'last_name'             => 'TestNewLastName',
				'email'                 => 'TestNewEmail@com.com',
				'password'              => 'TestNewPassword',
				'password_confirmation' => 'TestNewPassword',
				'suburb'                => 'TestNewSuburb',
				'postcode'              => '2345',
				'state'                 => 'ACT',
				'timezone'              => 'Australia/Brisbane',
			])
			->assertResponseStatus(200);

		// Check DB for change.
		$this->seeInDatabase('users', [
			'first_name' => 'TestNewFirstName',
			'last_name'  => 'TestNewLastName',
			'email'      => 'TestNewEmail@com.com',
			'suburb'     => 'TestNewSuburb',
			'postcode'   => '2345',
			'state'      => 'ACT',
			'timezone'   => 'Australia/Brisbane',
		]);
	}

}

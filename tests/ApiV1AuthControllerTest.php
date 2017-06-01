<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiV1AuthControllerTest extends TestCase
{
	// Api/V1/AuthController Test

	/**
	 * Test postSignup
	 * POST: /api/v1/auth/signup
	 */
	public function testPostSignup()
	{
		// Image stubs for `identification.*.file`
		$stub_1       = __DIR__ . '/test_stubs/test.jpg';
		$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);
		$stub_2       = __DIR__ . '/test_stubs/test2.jpg';
		$image_file_2 = new UploadedFile($stub_2, 'test2.jpg', filesize($stub_2), 'image/jpeg', null, true);

		// Post user data to signup API.
		$response = $this->call('POST', '/api/v1/auth/signup', [
			'first_name'            => 'TestFirstName',
			'last_name'             => 'TestLastName',
			'phone'                 => '12345678',
			'email'                 => 'TestNewEmail@com.com',
			'password'              => 'TestPassword',
			'password_confirmation' => 'TestPassword',
			'timezone'              => 'Australia/Brisbane',
			'account_type'          => 'Spectator',
			'suburb'                => 'Fakeville',
			'address_1'             => 'TestAddress1',
			'address_2'             => 'TestAddress2',
			'postcode'              => 1234,
			'state'                 => 'QLD',
			'identification'        => [
				[
					'type'                  => 'TestIdType1',
					'identification_number' => 'FakeIdNumber1',
				],
				[
					'type'                  => 'TestIdType2',
					'identification_number' => 'FakeIdNumber2',
				],
			],
		], [], [
			'identification.0.file' => $image_file_1,
			'identification.1.file' => $image_file_2,
		]);
		$this->assertEquals(200, $response->getStatusCode());

		// Check database for user creation.
		$this->seeInDatabase('users', [
			'first_name' => 'TestFirstName',
			'last_name'  => 'TestLastName',
			'phone'      => '12345678',
			'email'      => 'TestNewEmail@com.com',
			'timezone'   => 'Australia/Brisbane',
			'type'       => 'Spectator',
			'suburb'     => 'Fakeville',
			'address_1'  => 'TestAddress1',
			'address_2'  => 'TestAddress2',
			'postcode'   => 1234,
			'state'      => 'QLD',
		]);

		$user_created = App\User::where('email', 'TestNewEmail@com.com')->first();

		// Check DB for identification creation.
		$this->seeInDatabase('user_images', [
			'user_id'               => $user_created->id,
			'type'                  => 'TestIdType1',
			'identification_number' => 'FakeIdNumber1',
		]);
		$this->seeInDatabase('user_images', [
			'user_id'               => $user_created->id,
			'type'                  => 'TestIdType2',
			'identification_number' => 'FakeIdNumber2',
		]);

		// Check file has copied successfully
		$images_created = App\UserImage::where('user_id', $user_created->id)->get();
		foreach ($images_created as $image) {
			$this->assertTrue(Storage::disk('local')->exists('/public/user_images/' . $image->file));
		}
	}

	/**
	 * Test postLogin
	 * POST: /api/v1/auth/login
	 */
	public function testPostLogin()
	{
		// Create a test user
		$password = 'TestPassword';

		$user = factory(App\User::class)->create([
			'type'     => 'Spectator',
			'password' => bcrypt($password),
		]);

		// Try login, which will return token.

		// Test wrong password, return 401
		$this->post('/api/v1/auth/login', [
			'email'    => $user->email,
			'password' => str_random(10),
		])
			->assertResponseStatus(401);

		// Test correct password, returns token.
		$this->post('/api/v1/auth/login', [
			'email'    => $user->email,
			'password' => $password,
		])
			->assertResponseStatus(200)
			->seeJsonStructure([
				'token',
			]);
	}

	/**
	 * Test postLogin
	 * POST: /api/v1/auth/login
	 */
	public function testPostForgotPassword()
	{
		// Create a test user
		$user = factory(App\User::class)->create([
			'type' => 'Spectator',
		]);

		// Try login, which will return token.
		// Test with wrong email
		$this->post('/api/v1/auth/forgot-password', [
			'email' => $user->email . 'test',
		])
			->seeJsonStructure(['error']);

		// Test with correct email
		$this->post('/api/v1/auth/forgot-password', [
			'email' => $user->email,
		])
			->seeJsonStructure(['status']);
	}

}

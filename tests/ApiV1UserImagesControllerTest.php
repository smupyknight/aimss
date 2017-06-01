<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiV1UserImagesControllerTest extends TestCase
{
	// Api/V1/UserImagesController Test

	/**
	 * Test getIndex
	 * GET: /api/v1/user-images
	 */
	public function testGetIndex()
	{
		// Create a test user with Images.
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$images = factory(App\UserImage::class, 3)->create([
			'user_id' => $user->id,
		]);

		// Get list of images.
		$this->actingAs($user)
			->get('/api/v1/user-images?api_token=' . $api_token)
			->assertResponseStatus(200);

		foreach ($images as $image) {
			$this->see($image->file);
		}
	}

	/**
	 * Test postCreate
	 * POST: /api/v1/user-images/create
	 */
	public function testPostCreate()
	{
		// Create a test user with Images.
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Image stubs for `identification.*.file`
		$stub_1       = __DIR__ . '/test_stubs/test.jpg';
		$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);

		$response = $this->actingAs($user)
			->call('post', '/api/v1/user-images/create', [
				'api_token' => $api_token,
			], [], [
				'file' => $image_file_1,
			]);

		$this->assertEquals(200, $response->getStatusCode());
		$this->seeJsonStructure(['image_id']);

		$this->seeInDatabase('user_images', [
			'user_id' => $user->id,
		]);
	}

	/**
	 * Test postDelete
	 * POST: /api/v1/user-images/delete
	 */
	public function testPostDelete()
	{
		// Create a test user with Images.
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$image = factory(App\UserImage::class)->create([
			'user_id' => $user->id,
		]);

		// Do Delete
		$this->actingAs($user)
			->post('/api/v1/user-images/delete', [
				'api_token' => $api_token,
				'image_id'  => $image->id,
			])
			->assertResponseStatus(200);

		// Check DB
		$this->dontSeeInDatabase('user_images', [
			'user_id' => $user->id,
			'file'    => $image->file,
		]);

		// Check file has deleted.
		$this->assertFalse(Storage::disk('local')->exists('/public/user_images/' . $image->file));
	}

}

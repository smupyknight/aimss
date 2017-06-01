<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiV1MediaControllerTest extends TestCase
{
	// Api/V1/MediaController Test

	/**
	 * Test getindex
	 * POST: /api/v1/media/{submission_id}
	 */
	public function testGetIndex()
	{
		// Create test user, test event, test incident, test submission.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Add some media.
		$media = factory(App\SubmissionMedia::class, 5)->create([
			'submission_id' => $form_submission->id,
		]);

		// Test get list of media image.
		$this->actingAs($user)
			->get('/api/v1/media/' . $form_submission->id . '?api_token=' . $api_token)
			->assertResponseStatus(200);

		// Check response contains all information.
		foreach ($media as $one_media) {
			$this->see($one_media->filename);
			$this->see($one_media->type);
		}
	}

	/**
	 * Test postCreate
	 * POST: /api/v1/media/create/{submission_id}
	 */
	public function testPostCreate()
	{
		// Create test user, test event, test incident, test submission.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Image stubs for `.file`
		$stub_1       = __DIR__ . '/test_stubs/test.jpg';
		$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);

		// Test add media image.
		$this->actingAs($user)
			->call('post', '/api/v1/media/create/' . $form_submission->id, [
				'api_token' => $api_token,
				'type'      => 'Image',
			], [], [
				'file' => $image_file_1,
			]);

		// Check Db for creation.
		$this->seeInDatabase('submission_media', [
			'submission_id' => $form_submission->id,
			'type'          => 'Image',
		]);

		// Check file has copied.
		$media_created = App\SubmissionMedia::where('submission_id', $form_submission->id)->get()->last();
		$this->assertTrue(Storage::disk('public')->exists('/submission_media/' . $media_created->file));
	}

	// Helper functions.

	/**
	 * Create admin, user, event, incident, formCategory, formQuestion, formSubmission, formAnswer
	 */
	public function createTestingData()
	{
		// Create Admin user, test user.
		$api_token  = 'TestApiToken';
		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test event.
		$event = factory(App\Event::class)->create([
			'user_id' => $admin_user->id,
			'status'  => 'accepted',
		]);

		// Add incident.
		$incident = factory(App\Incident::class)->create([
			'user_id'  => $admin_user->id,
			'event_id' => $event->id,
		]);

		// Add form category.
		$form_category = factory(App\FormCategory::class)->create();

		// Add form question.
		$form_question = factory(App\FormQuestion::class)->create([
			'category_id' => $form_category->id,
			'created_by'  => $admin_user->id,
			'num'         => 1,
		]);

		// Acting user.
		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Add form_submission
		$form_submission = factory(App\FormSubmission::class)->create([
			'user_id'     => $user->id,
			'incident_id' => $incident->id,
		]);

		return [
			'admin'           => $admin_user,
			'user'            => $user,
			'event'           => $event,
			'incident'        => $incident,
			'form_category'   => $form_category,
			'form_submission' => $form_submission,
			'api_token'       => $api_token,
		];
	}

}

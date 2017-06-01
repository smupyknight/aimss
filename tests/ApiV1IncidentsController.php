<?php

use Carbon\Carbon;

class ApiV1IncidentsController extends TestCase
{
	// Api/V1/IncidentsController Test

	/**
	 * Test postCreate
	 * POST: /api/v1/incidents/create
	 */
	public function testPostCreate()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		$event = factory(App\Event::class)->create([
			'user_id' => $admin_user->id,
			'status'  => 'accepted',
		]);

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Do post create incident
		// Test with wrong event_id
		$this->actingAs($user)
			->post('/api/v1/incidents/create', [
				'api_token'     => $api_token,
				'event_id'      => 999,
				'incident_time' => Carbon::now()->toIso8601String(),
			])
			->assertResponseStatus(404);

		// Test with valid event_id
		$this->actingAs($user)
			->post('/api/v1/incidents/create', [
				'api_token'     => $api_token,
				'event_id'      => $event->id,
				'incident_time' => Carbon::now()->toIso8601String(),
			])
			->assertResponseStatus(200)
			->seeJsonStructure(['id']);

		// Check DB for incident creation.
		$this->seeInDatabase('incidents', [
			'user_id'  => $user->id,
			'event_id' => $event->id,
		]);
	}

	/**
	 * Test getList
	 * GET: /api/v1/incidents/list/{event_id}
	 */
	public function testGetList()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		$event = factory(App\Event::class)->create([
			'user_id' => $admin_user->id,
			'status'  => 'accepted',
		]);

		// Add incident.
		$incidents = factory(App\Incident::class, 2)->create([
			'user_id'  => $admin_user->id,
			'event_id' => $event->id,
		]);

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Do get list.
		$this->actingAs($user)
			->get('/api/v1/incidents/list/' . $event->id . '?api_token=' . $api_token)
			->assertResponseStatus(200);

		// See incidents
		foreach ($incidents as $incident) {
			$this->see($incident->name);
		}
	}

	/**
	 * Test getForm
	 * GET: /api/v1/incidents/form
	 */
	public function testGetForm()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		$event = factory(App\Event::class)->create([
			'user_id' => $admin_user->id,
			'status'  => 'accepted',
		]);

		// Add incident.
		$incidents = factory(App\Incident::class, 2)->create([
			'user_id'  => $admin_user->id,
			'event_id' => $event->id,
		]);

		// Add form categories.
		for ($i = 1; $i <= 2; $i++) {
			$form_category = factory(App\FormCategory::class)->create([
				'num' => $i,
			]);
		}

		// Add form questions.
		$form_categories = App\FormCategory::all();
		foreach ($form_categories as $form_category) {
			for ($i = 1; $i <= 2; $i++) {
				$after_question = App\FormQuestion::where('category_id', $form_category->id)->orderBy('num', 'asc')->get()->last();
				$num            = $after_question ? $after_question->num + 1 : 1;

				$form_question = factory(App\FormQuestion::class)->create([
					'category_id' => $form_category->id,
					'created_by'  => $admin_user->id,
					'num'         => $num,
				]);
			}
		}

		// Acting user.
		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Test get form.
		$this->actingAs($user)
			->get('/api/v1/incidents/form?api_token=' . $api_token)
			->assertResponseStatus(200);

		// Check response contains all categories, corresponding questions.
		foreach ($form_categories as $form_category) {
			$this->see($form_category->name);

			$questions = App\FormQuestion::where('category_id', $form_category->id)
				->where('show_to_' . $user->type, '!=', 'no')
				->get();

			foreach ($questions as $question) {
				$this->see($question->question);
			}
		}
	}

	/**
	 * Test postForm
	 * POST: /api/v1/incidents/form
	 */
	public function testPostForm()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

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

		// Add form categories.
		for ($i = 1; $i <= 2; $i++) {
			$form_category = factory(App\FormCategory::class)->create([
				'num' => $i,
			]);
		}

		// Add form questions.
		$form_categories = App\FormCategory::all();
		foreach ($form_categories as $form_category) {
			for ($i = 1; $i <= 2; $i++) {
				$after_question = App\FormQuestion::where('category_id', $form_category->id)->orderBy('num', 'asc')->get()->last();
				$num            = $after_question ? $after_question->num + 1 : 1;

				$form_question = factory(App\FormQuestion::class)->create([
					'category_id' => $form_category->id,
					'created_by'  => $admin_user->id,
					'num'         => $num,
				]);
			}
		}

		// Acting user.
		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$answers = [];
		// Get questions per categories.
		foreach ($form_categories as $form_category) {
			$questions = App\FormQuestion::where('category_id', $form_category->id)
				->where('show_to_' . $user->type, '!=', 'no')
				->get();
			foreach ($questions as $question) {
				$answers[$question->id] = ['Test Answer to ' . $question->id];
			}
		}

		// Test post form.
		$this->actingAs($user)
			->post('/api/v1/incidents/form', [
				'api_token'   => $api_token,
				'incident_id' => $incident->id,
				'answers'     => $answers,
			]);

		// Check DB for form_submission creation.
		$this->seeInDatabase('form_submissions', [
			'incident_id' => $incident->id,
			'user_id'     => $user->id,
		]);

		// Check DB for form_answers creation.
		$form_submission_created = App\FormSubmission::where('incident_id', $incident->id)
			->where('user_id', $user->id)
			->get()
			->first();

		foreach ($form_categories as $form_category) {
			$questions = App\FormQuestion::where('category_id', $form_category->id)
				->where('show_to_' . $user->type, '!=', 'no')
				->get();
			foreach ($questions as $question) {
				// Check db for questions.
				$this->seeInDatabase('form_answers', [
					'submission_id' => $form_submission_created->id,
					'question_id'   => $question->id,
					'answers'       => '["Test Answer to ' . $question->id . '"]',
				]);
			}
		}
	}

}

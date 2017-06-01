<?php

use Illuminate\Support\Facades\DB;

class ApiV1SubmissionsControllerTest extends TestCase
{
	// Api/V1/IncidentsController Test

	/**
	 * Test getIndex
	 * GET: /api/v1/submissions
	 */
	public function testGetIndex()
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

		// Acting user.
		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Add form submission.
		$form_submissions = factory(App\FormSubmission::class, 2)->create([
			'incident_id' => $incident->id,
			'user_id'     => $user->id,
		]);

		// Test get index.
		$this->actingAs($user)
			->get('/api/v1/submissions?api_token=' . $api_token)
			->assertResponseStatus(200);
		$response_json = json_decode($this->response->getContent());

		$this->assertEquals(count($form_submissions), count($response_json));
	}

	/**
	 * Test getView
	 * GET: /api/v1/submissions/view/{submission_id}
	 */
	public function testGetView()
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

		// Add form submission.
		$form_submission = factory(App\FormSubmission::class)->create([
			'incident_id' => $incident->id,
			'user_id'     => $user->id,
		]);

		// Get questions per categories.
		foreach ($form_categories as $form_category) {
			$questions = App\FormQuestion::where('category_id', $form_category->id)
				->where('show_to_' . $user->type, '!=', 'no')
				->get();
			foreach ($questions as $question) {
				DB::table('form_answers')->insert([
					'submission_id' => $form_submission->id,
					'question_id'   => $question->id,
					'answers'       => $question->question . 'Answer',
					'created_at'    => '2017-04-20 15:23:06',
					'updated_at'    => '2017-04-20 15:23:06',
				]);
			}
		}

		// Test get view.
		$this->actingAs($user)
			->get('/api/v1/submissions/view/' . $form_submission->id . '?api_token=' . $api_token)
			->assertResponseStatus(200)
			->seeJsonStructure([
				'id',
				'incident_id',
				'event_id',
				'name',
				'created_at',
				'updated_at',
				'categories',
			]);

		// See details.
		$form_answers = App\FormAnswer::where([
			'submission_id' => $form_submission->id,
		]);
		foreach ($form_answers as $form_answer) {
			$this->see($form_answer->answer);
		}
	}

}

<?php

use Carbon\Carbon;

class ApiV1InjuriesControllerTest extends TestCase
{
	// Api/V1/IncidentsController Test

	/**
	 * Test postCreate
	 * POST: /api/v1/injuries/create
	 */
	public function testPostCreate()
	{
		// Create env.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Do post
		$data_post = [
			'first_name'                    => 'TestInjuryFirstName',
			'last_name'                     => 'TestInjuryLastName',
			'dob'                           => '2017-12-12',
			'head_eye_which'                => 'R',
			'extremities_buttocks_which'    => 'L',
			'extremities_upperarm_which'    => 'B',
			'extremities_lowerarm_which'    => 'R',
			'extremities_hand_injury_which' => 'L',
			'extremities_upperleg_which'    => 'B',
			'extremities_lowerleg_which'    => 'R',
			'extremities_foot_which'        => 'L',
			'responder_doctor_time'         => Carbon::now()->addDays(1)->toIso8601String(),
			'responder_paramedic_time'      => Carbon::now()->addDays(2)->toIso8601String(),
			'responder_official_time'       => Carbon::now()->addDays(3)->toIso8601String(),
			'responder_competitor_time'     => Carbon::now()->addDays(4)->toIso8601String(),
			'responder_spectator_time'      => Carbon::now()->addDays(5)->toIso8601String(),
			'responder_other1_time'         => Carbon::now()->addDays(6)->toIso8601String(),
			'responder_other2_time'         => Carbon::now()->addDays(7)->toIso8601String(),
			'resource_medicalcar_time'      => Carbon::now()->addDays(8)->toIso8601String(),
			'resource_extricationunit_time' => Carbon::now()->addDays(9)->toIso8601String(),
			'resource_ambulance_time'       => Carbon::now()->addDays(10)->toIso8601String(),
			'resource_cuttingvehicle_time'  => Carbon::now()->addDays(12)->toIso8601String(),
			'resource_fireunit_time'        => Carbon::now()->addDays(13)->toIso8601String(),
			'resource_helicopter_time'      => Carbon::now()->addDays(14)->toIso8601String(),
			'resource_other1_time'          => Carbon::now()->addDays(15)->toIso8601String(),
			'resource_other2_time'          => Carbon::now()->addDays(16)->toIso8601String(),
			'transfer_medicalcentre_time'   => Carbon::now()->addDays(17)->toIso8601String(),
			'transfer_hospital_time'        => Carbon::now()->addDays(18)->toIso8601String(),
		];

		$this->actingAs($user)
			->post('/api/v1/injuries/create', array_merge($data_post, [
				'api_token'     => $api_token,
				'submission_id' => $form_submission->id,
			]))
			->assertResponseStatus(200);

		// Ensure can create several.
		$data_post_2 = [
			'first_name'                    => 'TestInjuryFirstName2',
			'last_name'                     => 'TestInjuryLastName2',
			'dob'                           => '2017-12-12',
			'head_eye_which'                => 'B',
			'extremities_buttocks_which'    => 'L',
			'extremities_upperarm_which'    => 'R',
			'extremities_lowerarm_which'    => 'B',
			'extremities_hand_injury_which' => 'L',
			'extremities_upperleg_which'    => 'R',
			'extremities_lowerleg_which'    => 'B',
			'extremities_foot_which'        => 'L',
			'responder_doctor_time'         => Carbon::now()->addDays(-1)->toIso8601String(),
			'responder_paramedic_time'      => Carbon::now()->addDays(-2)->toIso8601String(),
			'responder_official_time'       => Carbon::now()->addDays(-3)->toIso8601String(),
			'responder_competitor_time'     => Carbon::now()->addDays(-4)->toIso8601String(),
			'responder_spectator_time'      => Carbon::now()->addDays(-5)->toIso8601String(),
			'responder_other1_time'         => Carbon::now()->addDays(-6)->toIso8601String(),
			'responder_other2_time'         => Carbon::now()->addDays(-7)->toIso8601String(),
			'resource_medicalcar_time'      => Carbon::now()->addDays(-8)->toIso8601String(),
			'resource_extricationunit_time' => Carbon::now()->addDays(-9)->toIso8601String(),
			'resource_ambulance_time'       => Carbon::now()->addDays(-10)->toIso8601String(),
			'resource_cuttingvehicle_time'  => Carbon::now()->addDays(-12)->toIso8601String(),
			'resource_fireunit_time'        => Carbon::now()->addDays(-13)->toIso8601String(),
			'resource_helicopter_time'      => Carbon::now()->addDays(-14)->toIso8601String(),
			'resource_other1_time'          => Carbon::now()->addDays(-15)->toIso8601String(),
			'resource_other2_time'          => Carbon::now()->addDays(-16)->toIso8601String(),
			'transfer_medicalcentre_time'   => Carbon::now()->addDays(-17)->toIso8601String(),
			'transfer_hospital_time'        => Carbon::now()->addDays(-18)->toIso8601String(),
		];

		$this->actingAs($user)
			->post('/api/v1/injuries/create', array_merge($data_post_2, [
				'api_token'     => $api_token,
				'submission_id' => $form_submission->id,
			]))
			->assertResponseStatus(200);

		// Check DB for creation.
		$this->seeInDatabase('injuries', array_merge($data_post, [
			'submission_id' => $form_submission->id,
		]));
		$this->seeInDatabase('injuries', array_merge($data_post_2, [
			'submission_id' => $form_submission->id,
		]));
	}

	/**
	 * Test postEdit
	 * POST: /api/v1/injuries/edit/{injury_id}
	 */
	public function testPostEdit()
	{
		// Create env.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Create injuries.
		$injuries = factory(App\Injury::class, 3)->create([
			'submission_id' => $form_submission->id,
		]);

		// Do post edit
		$data_post = [
			'dob'                           => '2017-12-12',
			'head_eye_which'                => 'B',
			'extremities_buttocks_which'    => 'R',
			'extremities_upperarm_which'    => 'L',
			'extremities_lowerarm_which'    => 'B',
			'extremities_hand_injury_which' => 'R',
			'extremities_upperleg_which'    => 'L',
			'extremities_lowerleg_which'    => 'B',
			'extremities_foot_which'        => 'R',
			'responder_doctor_time'         => Carbon::now()->addDays(-1)->toIso8601String(),
			'responder_paramedic_time'      => Carbon::now()->addDays(-2)->toIso8601String(),
			'responder_official_time'       => Carbon::now()->addDays(-3)->toIso8601String(),
			'responder_competitor_time'     => Carbon::now()->addDays(-4)->toIso8601String(),
			'responder_spectator_time'      => Carbon::now()->addDays(-5)->toIso8601String(),
			'responder_other1_time'         => Carbon::now()->addDays(-6)->toIso8601String(),
			'responder_other2_time'         => Carbon::now()->addDays(-7)->toIso8601String(),
			'resource_medicalcar_time'      => Carbon::now()->addDays(-8)->toIso8601String(),
			'resource_extricationunit_time' => Carbon::now()->addDays(-9)->toIso8601String(),
			'resource_ambulance_time'       => Carbon::now()->addDays(-10)->toIso8601String(),
			'resource_cuttingvehicle_time'  => Carbon::now()->addDays(-12)->toIso8601String(),
			'resource_fireunit_time'        => Carbon::now()->addDays(-13)->toIso8601String(),
			'resource_helicopter_time'      => Carbon::now()->addDays(-14)->toIso8601String(),
			'resource_other1_time'          => Carbon::now()->addDays(-15)->toIso8601String(),
			'resource_other2_time'          => Carbon::now()->addDays(-16)->toIso8601String(),
			'transfer_medicalcentre_time'   => Carbon::now()->addDays(-17)->toIso8601String(),
			'transfer_hospital_time'        => Carbon::now()->addDays(-18)->toIso8601String(),
		];

		$this->actingAs($user)
			->post('/api/v1/injuries/edit/' . $injuries[0]->id, array_merge($data_post, [
				'api_token' => $api_token,
			]))
			->assertResponseStatus(200);

		// Check DB for edit.
		$this->seeInDatabase('injuries', array_merge($data_post, [
			'submission_id' => $form_submission->id,
		]));
	}

	/**
	 * Test postDelete
	 * POST: /api/v1/injuries/delete/{injury_id}
	 */
	public function testPostDelete()
	{
		// Create env.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Create injury.
		$injury = factory(App\Injury::class)->create([
			'submission_id' => $form_submission->id,
		]);

		// Insure db creation.
		$this->seeInDatabase('injuries', [
			'id' => $injury->id,
		]);

		// Do post delete.
		$this->actingAs($user)
			->post('/api/v1/injuries/delete/' . $injury->id, [
				'api_token' => $api_token,
			])
			->assertResponseStatus(200);

		// Check DB for delete.
		$this->dontSeeInDatabase('injuries', [
			'id' => $injury->id,
		]);
	}

	/**
	 * Test getView
	 * GET: /api/v1/injuries/view/{injury_id}
	 */
	public function testGetView()
	{
		// Create env.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Create injury.
		$injury = factory(App\Injury::class)->create([
			'submission_id' => $form_submission->id,
		]);

		// Insure db creation.
		$this->seeInDatabase('injuries', [
			'id' => $injury->id,
		]);

		// Do get view.
		$this->actingAs($user)
			->get('/api/v1/injuries/view/' . $injury->id . '?api_token=' . $api_token)
			->assertResponseStatus(200)
			->see($injury->first_name)
			->see($injury->last_name);
	}

	/**
	 * Test getList
	 * GET: /api/v1/injuries/list/{submission_id}
	 */
	public function testGetList()
	{
		// Create env.
		$my_env          = $this->createTestingData();
		$user            = $my_env['user'];
		$api_token       = $my_env['api_token'];
		$form_submission = $my_env['form_submission'];

		// Create injuries.
		$injuries = factory(App\Injury::class, 5)->create([
			'submission_id' => $form_submission->id,
		]);

		// Insure db creation.
		foreach ($injuries as $injury) {
			$this->seeInDatabase('injuries', [
				'id' => $injury->id,
			]);
		}

		// Do get list.
		$this->actingAs($user)
			->get('/api/v1/injuries/list/' . $form_submission->id . '?api_token=' . $api_token)
			->assertResponseStatus(200);

		foreach ($injuries as $injury) {
			$this->see($injury->first_name);
			$this->see($injury->last_name);
		}
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

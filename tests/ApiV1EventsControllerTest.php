<?php

use Carbon\Carbon;

class ApiV1EventsControllerTest extends TestCase
{
	// Api/V1/EventsController Test

	/**
	 * Test getIndex
	 * GET: /api/v1/events
	 */
	public function testGetIndex()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		$events_1 = factory(App\Event::class, 5)->create([
			'user_id'    => $admin_user->id,
			'start_date' => '2017-04-19 00:00:00',
			'end_date'   => '2017-04-20 00:00:00',
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		$events_2 = factory(App\Event::class, 5)->create([
			'user_id'    => $admin_user->id,
			'start_date' => '2017-04-17 00:00:00',
			'end_date'   => '2017-04-18 00:00:00',
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$events_mine = factory(App\Event::class)->create([
			'user_id'    => $user->id,
			'start_date' => '2017-04-19 00:00:00',
			'end_date'   => '2017-04-19 00:00:00',
			'status'     => 'pending',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		// Do get events.
		$this->actingAs($user)
			->call('get', '/api/v1/events', [
				'api_token'  => $api_token,
				'start_date' => '2017-04-18',
				'end_date'   => '2017-04-21',
				'longitude'  => '1',
				'latitude'   => '1',
			]);

		$this->assertResponseStatus(200)
			->seeJsonStructure([
				'total',
				'per_page',
				'current_page',
				'last_page',
				'from',
				'to',
				'data',
			]);

		// Check filter.
		foreach ($events_1 as $event) {
			$this->see($event->name);
		}

		$this->see($events_mine->name);

		foreach ($events_2 as $event) {
			$this->dontSee($event->name);
		}
	}

	/**
	 * Test getView
	 * GET: /api/v1/events/{event_id}
	 */
	public function testGetView()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		$event = factory(App\Event::class)->create([
			'user_id'    => $admin_user->id,
			'start_date' => '2017-04-19 14:00:00',
			'end_date'   => '2017-04-20 14:00:00',
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		// Add event_stage
		$event_stage = factory(App\EventStage::class)->create([
			'event_id' => $event->id,
		]);

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Add incident
		$incident = factory(App\Incident::class)->create([
			'user_id'  => $user->id,
			'event_id' => $event->id,
		]);

		// Do get view.
		// Test wrong event
		$this->actingAs($user)
			->call('get', '/api/v1/events/999', [
				'api_token' => $api_token,
			]);
		$this->assertResponseStatus(404);

		$this->actingAs($user)
			->call('get', '/api/v1/events/' . $event->id, [
				'api_token' => $api_token,
			]);

		$this->assertResponseStatus(200)
			->seeJsonStructure([
				'event_stages',
				'incidents',
			]);

		// See name
		$this->see($event->name);

		// See event_stage
		$this->see($event_stage->stage_number);

		// See incident
		$this->see($incident->id);
	}

	/**
	 * Test getList
	 * GET: /api/v1/events/list/{filter}
	 */
	public function testGetList()
	{
		// Create Admin user, test user.
		$api_token = 'TestApiToken';

		$admin_user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		// Create test events.
		// future
		$one_month_later = Carbon::now()->addMonth(1);
		$events_future   = factory(App\Event::class, 5)->create([
			'user_id'    => $admin_user->id,
			'start_date' => $one_month_later->toDateTimeString(),
			'end_date'   => $one_month_later->toDateTimeString(),
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		$one_month_before = Carbon::now()->addMonth(-1);
		$events_past      = factory(App\Event::class, 5)->create([
			'user_id'    => $admin_user->id,
			'start_date' => $one_month_before->toDateTimeString(),
			'end_date'   => $one_month_before->toDateTimeString(),
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		$events_now = factory(App\Event::class, 5)->create([
			'user_id'    => $admin_user->id,
			'start_date' => Carbon::now()->addDay(-7),
			'end_date'   => Carbon::now()->addDay(7),
			'status'     => 'accepted',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		$events_mine = factory(App\Event::class)->create([
			'user_id'    => $user->id,
			'start_date' => Carbon::now()->toDateTimeString(),
			'end_date'   => Carbon::now()->toDateTimeString(),
			'status'     => 'pending',
			'longitude'  => '1',
			'latitude'   => '1',
		]);

		// Do get future events.
		$this->actingAs($user)
			->call('get', '/api/v1/events/list/future', [
				'api_token' => $api_token,
			]);

		$this->assertResponseStatus(200)
			->seeJsonStructure([
				'total',
				'per_page',
				'current_page',
				'last_page',
				'from',
				'to',
				'data',
			]);

		// Check filter.
		foreach ($events_future as $event) {
			$this->see($event->name);
		}

		foreach ($events_now as $event) {
			$this->dontSee($event->name);
		}

		foreach ($events_past as $event) {
			$this->dontSee($event->name);
		}

		// Do get now events.
		$this->actingAs($user)
			->call('get', '/api/v1/events/list/current', [
				'api_token' => $api_token,
			]);

		$this->assertResponseStatus(200)
			->seeJsonStructure([
				'total',
				'per_page',
				'current_page',
				'last_page',
				'from',
				'to',
				'data',
			]);

		// Check filter.
		foreach ($events_future as $event) {
			$this->dontSee($event->name);
		}

		foreach ($events_now as $event) {
			$this->see($event->name);
		}

		foreach ($events_past as $event) {
			$this->dontSee($event->name);
		}

		// Do get past events.
		$this->actingAs($user)
			->call('get', '/api/v1/events/list/past', [
				'api_token' => $api_token,
			]);

		$this->assertResponseStatus(200)
			->seeJsonStructure([
				'total',
				'per_page',
				'current_page',
				'last_page',
				'from',
				'to',
				'data',
			]);

		// Check filter.
		foreach ($events_future as $event) {
			$this->dontSee($event->name);
		}

		foreach ($events_now as $event) {
			$this->dontSee($event->name);
		}

		foreach ($events_past as $event) {
			$this->see($event->name);
		}
	}

	/**
	 * Test postCreate
	 * POST: /api/v1/events/create
	 */
	public function testPostCreate()
	{
		// Create a test user.
		$api_token = 'TestApiToken';

		$user = factory(App\User::class)->create([
			'type'      => 'Spectator',
			'api_token' => $api_token,
		]);

		// Do create an event.
		$this->actingAs($user)
			->post('/api/v1/events/create', [
				'api_token'  => $api_token,
				'name'       => 'MyTestEvent_1',
				'location'   => 'TestLocation',
				'start_date' => Carbon::now()->toIso8601String(),
				'end_date'   => Carbon::now()->toIso8601String(),
				'timezone'   => 'Australia/Brisbane',
			])
			->assertResponseStatus(200)
			->seeJsonStructure(['event_id']);

		// Check DB for creation.
		$this->seeInDatabase('events', [
			'user_id'    => $user->id,
			'name'       => 'MyTestEvent_1',
			'location'   => 'TestLocation',
			'start_date' => (new Carbon(Carbon::now()->toIso8601String(), 'Australia/Brisbane'))->setTimezone('UTC'),
			'end_date'   => (new Carbon(Carbon::now()->toIso8601String(), 'Australia/Brisbane'))->setTimezone('UTC'),
			'timezone'   => 'Australia/Brisbane',
		]);
	}

}

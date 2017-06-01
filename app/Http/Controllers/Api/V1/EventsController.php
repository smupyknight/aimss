<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Event;
use App\Incident;
use App\EventStage;
use Carbon\Carbon;
use Auth;

class EventsController extends Controller
{

	/**
	 * Retrieve paginated list of events based on date and user's location.
	 */
	public function getIndex(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'date_format:Y-m-d',
			'end_date'   => 'date_format:Y-m-d',
		]);

		$query = Event::orderBy('id', 'desc');

		if ($request->start_date) {
			$date = Carbon::parse($request->start_date, 'Australia/Sydney')->setTimezone('UTC');
			$query->where('start_date', '>=', $date);
		}

		if ($request->end_date) {
			$date = Carbon::parse($request->end_date, 'Australia/Sydney')->setTimezone('UTC');
			$query->where('end_date', '<=', $date);
		}

		if ($request->longitude && $request->latitude) {
			$query->whereBetween('longitude', [$request->longitude - 1, $request->longitude + 1]);
			$query->whereBetween('latitude', [$request->latitude - 1, $request->latitude + 1]);
		}

		$query->where('status', 'accepted')->orWhere('user_id', $this->user->id);

		$events = $query->paginate(15);
		$data = [];

		foreach ($events as $event) {
			$data[] = [
				'id'            => $event->id,
				'name'          => $event->name,
				'location'      => $event->location,
				'description'   => $event->description,
				'num_incidents' => $event->incidents->count(),
				'start_date'    => $event->start_date->setTimezone($event->timezone)->format('c'),
			];
		}

		$results = [
			'total'         => $events->total(),
			'per_page'      => $events->perPage(),
			'current_page'  => $events->currentPage(),
			'last_page'     => $events->lastPage(),
			'from'          => $events->firstItem(),
			'to'            => $events->lastItem(),
			'data'          => $data,
		];

		return response()->json($results);
	}

	public function getView($event_id)
	{
		$event = Event::findOrFail($event_id);

		if ($event->status != 'accepted' && $event->user_id != $this->user->id) {
			abort(404);
		}

		$result = [
			'id'                           => $event->id,
			'name'                         => $event->name,
			'type'                         => $event->type,
			'style'                        => $event->style,
			'competition_service'          => $event->competition_service,
			'competitor_progression'       => $event->competitor_progression,
			'competitor_progression_other' => $event->competitor_progression_other,
			'medical_park_firstaid'        => $event->medical_park_firstaid,
			'medical_park_ambulance'       => $event->medical_park_ambulance,
			'medical_park_other'           => $event->medical_park_other,
			'medical_route_firstaid'       => $event->medical_route_firstaid,
			'medical_route_ambulance'      => $event->medical_route_ambulance,
			'medical_route_other'          => $event->medical_route_other,
			'spectator'                    => $event->spectator,
			'competitive_distance'         => $event->competitive_distance,
			'competitor_number'            => $event->competitor_number,
			'location'                     => $event->location,
			'description'                  => $event->description,
			'start_date'                   => $event->start_date->setTimezone($event->timezone)->format('c'),
			'end_date'                     => $event->end_date->setTimezone($event->timezone)->format('c'),
			'timezone'                     => $event->timezone,
			'created_at'                   => $event->created_at->format('c'),
			'updated_at'                   => $event->updated_at->format('c'),
			'num_incidents'                => count($event->incidents),
		];

		$event_stages = [];

		foreach ($event->stages as $stage) {
			$event_stages[] = [
				'id'           => $stage->id,
				'stage_number' => $stage->stage_number,
				'distance'     => $stage->distance,
				'fastest_time' => $stage->fastest_time,
				'created_at'   => $stage->created_at->format('c'),
				'updated_at'   => $stage->updated_at->format('c'),
			];
		}

		$result['event_stages'] = $event_stages;

		$incidents = [];

		foreach ($event->incidents as $incident) {
			$incidents[] = [
				'incident_id'   => $incident->id,
				'incident_name' => $event->name . ' on '. $incident->incident_time->setTimezone($event->timezone)->format('l, j M \a\t g:ia'),
				'reported_date' => $incident->incident_time->format('c'),
			];
		}

		$result['incidents'] = $incidents;

		return response()->json($result);
	}

	public function getList($filter)
	{
		if ($filter == 'current') {
			$query = Event::orderBy('start_date');
			$query->where('start_date', '<=', Carbon::now());
			$query->where('end_date', '>=', Carbon::now());
		}

		if ($filter == 'future') {
			$query = Event::orderBy('start_date');
			$query->where('start_date', '>', Carbon::now());
		}

		if ($filter == 'past') {
			$query = Event::orderBy('start_date', 'desc');
			$query->where('end_date', '<', Carbon::now());
		}

		$query->where('status', 'accepted')->orWhere('user_id', $this->user->id);

		$events = $query->paginate(10);
		$data = [];

		foreach ($events as $event) {
			$data[] = [
				'id'            => $event->id,
				'name'          => $event->name,
				'location'      => $event->location,
				'description'   => $event->description,
				'num_incidents' => $event->incidents->count(),
				'start_date'    => (new Carbon($event->start_date))->setTimezone($event->timezone)->format('c')
			];
		}

		$results = [
			'total'         => $events->total(),
			'per_page'      => $events->perPage(),
			'current_page'  => $events->currentPage(),
			'last_page'     => $events->lastPage(),
			'next_page_url' => $events->nextPageUrl(),
			'prev_page_url' => $events->previousPageUrl(),
			'from'          => $events->firstItem(),
			'to'            => $events->lastItem(),
			'data'          => $data
		];

		return response()->json($results);
	}

	/**
	 * Endpoint for allowing users to create an event with minimal information
	 * if it doesn't exist.
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'       => 'required',
			'location'   => 'required',
			'start_date' => 'required|date_format:' . Carbon::ISO8601,
			'end_date'   => 'required|date_format:' . Carbon::ISO8601,
			'timezone'   => 'required|timezone',
		]);

		$event = Event::create([
			'name'       => $request->name,
			'user_id'    => $this->user->id,
			'status'     => 'pending',
			'location'   => $request->location,
			'start_date' => (new Carbon($request->start_date, $request->timezone))->setTimezone('UTC'),
			'end_date'   => (new Carbon($request->end_date, $request->timezone))->setTimezone('UTC'),
			'timezone'   => $request->timezone,
		]);

		return response()->json(['event_id' => $event->id]);
	}

}

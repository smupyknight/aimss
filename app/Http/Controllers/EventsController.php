<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\EventStage;

class EventsController extends Controller
{

	/**
	 * Show list of all Events
	 */
	public function getIndex(Request $request)
	{
		return $this->getList($request);
	}

	public function getList(Request $request, $type = 'recent')
	{
		$query = Event::where('events.status', 'accepted')
			->leftJoin('incidents AS i', 'i.event_id', '=', 'events.id')
			->leftJoin('form_submissions AS s', 's.incident_id', '=', 'i.id')
			->selectRaw('events.*, COUNT(DISTINCT(i.id)) AS num_incidents, COUNT(DISTINCT(s.id)) AS num_submissions');

		if ($type == 'future') {
			$query->where('start_date', '>', Carbon::now());
			$query->orderBy('start_date', 'asc');
		} elseif ($type == 'past') {
			$query->where('start_date', '<', Carbon::parse('7 days ago'));
			$query->orderBy('start_date', 'desc');
		} else {
			$query->where('start_date', '>=', Carbon::parse('7 days ago'));
			$query->where('start_date', '<=', Carbon::now());
			$query->orderBy('start_date', 'desc');
			$type = 'recent';
		}

		if ($request->search) {
			$query->where('name', 'LIKE', '%' . $request->search . '%');
		}

		$events = $query->paginate(25);

		return view('pages.events-list')
			->with('events', $events)
			->with('type', $type)
			->with('title', 'Events');
	}

	public function getPending(Request $request)
	{
		$query = Event::where('events.status', 'pending')
			->join('incidents AS i', 'i.event_id', '=', 'events.id')
			->join('form_submissions AS s', 's.incident_id', '=', 'i.id')
			->join('users AS u', 'events.user_id', '=', 'u.id')
			->orderBy('start_date', 'asc')
			->selectRaw("events.*, CONCAT(u.first_name, ' ', u.last_name) AS user_name, COUNT(DISTINCT(i.id)) AS num_incidents, COUNT(DISTINCT(s.id)) AS num_submissions");

		if ($request->search) {
			$query->where('name', 'LIKE', '%' . $request->search . '%');
		}

		$events = $query->paginate(25);

		return view('pages.events-pending')
			->with('events', $events)
			->with('title', 'Pending Events');
	}

	/**
	 * Show create event form
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.events-create');
	}

	/**
	 * Handle saving of event data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'         => 'required',
			'type'         => 'required',
			'style'        => 'required',
			'start_date'   => 'required|date_format:d/m/Y H:i',
			'end_date'     => 'required|date_format:d/m/Y H:i',
			'timezone'     => 'required',
		]);

		$event = new Event;
		$event->user_id = Auth::user()->id;
		$event->camms_id = $request->camms_id;
		$event->name = $request->get('name', '');
		$event->status = 'accepted';
		$event->type = $request->get('type', '');
		$event->style = $request->get('style', '');
		$event->competition_service = $request->get('competition_service', '');
		$event->competitor_progression = $request->get('competitor_progression', '');

		if ($request->competitor_progression == 'Other') {
			$event->competitor_progression_other = $request->get('competitor_progression_other', '');
		}

		$event->medical_park_firstaid = $request->get('medical_park_firstaid', '');
		$event->medical_park_ambulance = $request->get('medical_park_ambulance', '');

		if ($request->medical_park == 'Other') {
			$event->medical_park_other = $request->get('medical_park_other', '');
		}

		$event->medical_route_firstaid = $request->get('medical_route_firstaid', '');
		$event->medical_route_ambulance = $request->get('medical_route_ambulance', '');

		if ($request->medical_route == 'Other') {
			$event->medical_route_other = $request->get('medical_route_other', '');
		}

		$event->spectator = $request->get('spectator', '');
		$event->competitive_distance = $request->get('competitive_distance', '');
		$event->competitor_number = $request->get('competitor_number', '');
		$event->location = $request->get('location', '');
		$event->latitude = $request->get('latitude', '');
		$event->longitude = $request->get('longitude', '');
		$event->description = $request->get('description', '');
		$event->start_date = Carbon::createFromFormat('d/m/Y H:i', $request->start_date, $request->timezone)->setTimezone('UTC');
		$event->end_date = Carbon::createFromFormat('d/m/Y H:i', $request->end_date, $request->timezone)->setTimezone('UTC');
		$event->timezone = $request->get('timezone', '');
		$event->save();

		return redirect('/events/view/'.$event->id);
	}

	/**
	 * Show edit event page
	 * @param  int $event_id
	 * @return view
	 */
	public function getEdit($event_id)
	{
		$event = Event::findOrFail($event_id);

		return view('pages.events-edit')
			->with('event', $event)
			->with('title', 'Edit Event');
	}

	/**
	 * Handle edit event data
	 * @param  Request $request
	 * @param  int  $event_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $event_id)
	{
		$this->validate($request, [
			'name'       => 'required',
			'status'     => 'required|in:pending,accepted',
			'type'       => 'required',
			'style'      => 'required',
			'start_date' => 'required|date_format:d/m/Y H:i',
			'end_date'   => 'required|date_format:d/m/Y H:i',
			'timezone'   => 'required',
		]);

		$event = Event::findOrFail($event_id);
		$event->camms_id = $request->camms_id;
		$event->name = $request->get('name', '');
		$event->status = $request->get('status');
		$event->type = $request->get('type', '');
		$event->style = $request->get('style', '');
		$event->competition_service = $request->get('competition_service', '');
		$event->competitor_progression = $request->get('competitor_progression', '');

		if ($request->competitor_progression == 'Other') {
			$event->competitor_progression_other = $request->get('competitor_progression_other', '');
		}

		$event->medical_park_firstaid = $request->get('medical_park_firstaid', '');
		$event->medical_park_ambulance = $request->get('medical_park_ambulance', '');

		if ($request->medical_park == 'Other') {
			$event->medical_park_other = $request->get('medical_park_other', '');
		}

		$event->medical_route_firstaid = $request->get('medical_route_firstaid', '');
		$event->medical_route_ambulance = $request->get('medical_route_ambulance', '');

		if ($request->medical_route == 'Other') {
			$event->medical_route_other = $request->get('medical_route_other', '');
		}

		$event->spectator = $request->get('spectator', '');
		$event->competitive_distance = $request->get('competitive_distance', '');
		$event->competitor_number = $request->get('competitor_number', '');
		$event->location = $request->get('location', '');
		$event->latitude = $request->get('latitude', '');
		$event->longitude = $request->get('longitude', '');
		$event->description = $request->get('description', '');
		$event->start_date = Carbon::createFromFormat('d/m/Y H:i', $request->start_date, $request->timezone)->setTimezone('UTC');
		$event->end_date = Carbon::createFromFormat('d/m/Y H:i', $request->end_date, $request->timezone)->setTimezone('UTC');
		$event->timezone = $request->get('timezone', '');
		$event->save();

		return redirect('/events/view/' . $event->id);
	}

	/**
	 * Show view event page
	 * @param  int $event_id
	 * @return view
	 */
	public function getView($event_id)
	{
		$event = Event::findOrFail($event_id);

		$incidents = $event->incidents()
			->leftJoin('form_submissions AS s', 's.incident_id', '=', 'incidents.id')
			->groupBy('incidents.id')
			->orderBy('incidents.incident_time', 'asc')
			->selectRaw('incidents.*, COUNT(s.id) AS num_reports')
			->get();

		return view('pages.events-view')
			->with('event', $event)
			->with('incidents', $incidents)
			->with('title', 'View Event');
	}

	/**
	 * Delete Event
	 * @param  Request $request
	 * @param  int $event_id
	 * @return redirect
	 */
	public function getDelete(Request $request, $event_id)
	{
		Event::where('id', $event_id)->delete();

		if ($request->ajax()) {
			return ['status' => 'success'];
		}

		return redirect('/events');
	}

	public function postAddStage(Request $request)
	{
		$this->validate($request, [
			'event_id'     => 'required|numeric',
			'stage_number' => 'required|numeric',
			'distance'     => 'required|numeric',
			'fastest_time' => 'required'
		]);

		$event = Event::findOrFail($request->event_id);

		$event_stage = new EventStage;
		$event_stage->event_id = $request->event_id;
		$event_stage->stage_number = $request->stage_number;
		$event_stage->distance = $request->distance;
		$event_stage->fastest_time = $event_stage->parseFastestTime($request->fastest_time);

		$event_stage->save();

		return redirect('/events/view/'.$event->id);
	}

	public function postEditStage(Request $request, $event_stage_id)
	{
		$this->validate($request, [
			'event_id'     => 'required|numeric',
			'stage_number' => 'required|numeric',
			'distance'     => 'required|numeric',
			'fastest_time' => 'required'
		]);

		$event_stage = EventStage::findOrFail($event_stage_id);

		$event_stage->stage_number = $request->stage_number;
		$event_stage->distance = $request->distance;
		$event_stage->fastest_time = $event_stage->parseFastestTime($request->fastest_time);

		$event_stage->save();

		return redirect('/events/view/'.$event_stage->event_id);
	}

	public function postDeleteStage(Request $request,$event_stage_id)
	{
		EventStage::where('id', $event_stage_id)->delete();

		return response()->json(true);
	}

}

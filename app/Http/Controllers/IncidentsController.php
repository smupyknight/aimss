<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;

class IncidentsController extends Controller
{

	public function getView($incident_id)
	{
		$incident = Incident::findOrFail($incident_id);

		return view('pages.incidents-view')
			->with('incident', $incident);
	}

}

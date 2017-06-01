<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
	protected $guarded = [];

	protected $dates = [
		'created_at',
		'updated_at',
		'incident_time'
	];

	public function formSubmissions()
	{
		return $this->hasMany('App\FormSubmission');
	}

	public function event()
	{
		return $this->belongsTo('App\Event');
	}

}

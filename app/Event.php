<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $guarded = [];

	protected $dates = [
		'created_at',
		'updated_at',
		'start_date',
		'end_date'
	];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function stages()
	{
		return $this->hasMany('App\EventStage');
	}

	public function incidents()
	{
		return $this->hasMany('App\Incident');
	}

}

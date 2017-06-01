<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{

	protected $guarded = [];

	/**
	 * Get the user record associated with the invitation.
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}

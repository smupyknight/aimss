<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCategory extends Model
{

	protected $guarded = [];

	public function questions()
	{
		return $this->hasMany('App\FormQuestion', 'category_id')->orderBy('num', 'asc');
	}

}

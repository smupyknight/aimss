<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $user = null;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			$this->user = request()->api_token ? Auth::guard('api')->user() : Auth::user();

			return $next($request);
		});
	}

}
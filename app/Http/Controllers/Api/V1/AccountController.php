<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

	public function getIndex(Request $request)
	{
		$user = Auth::guard('api')->user();

		$result = [
			'first_name'            => $user->first_name,
			'last_name'             => $user->last_name,
			'email'                 => $user->email,
			'address_1'             => $user->address_1,
			'address_2'             => $user->address_2,
			'suburb'                => $user->suburb,
			'postcode'              => $user->postcode,
			'state'                 => $user->state,
			'phone'                 => $user->phone,
			'type'                  => $user->type,
			'is_subscribed'         => $user->is_subscribed,
			'identification_type'   => $user->identification_type,
			'identification_number' => $user->identification_number
		];

		return response()->json($result);
	}

	public function postEdit(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'last_name'  => 'required|max:255',
			'email'      => 'required|email|max:255|unique:users,email,'.$this->user->id,
			'password'   => 'confirmed',
			'timezone'   => 'required',
		]);

		$user = User::findOrFail($this->user->id);
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->phone = $request->get('phone', '');
		$user->address_1 = $request->get('address_1', '');
		$user->address_2 = $request->get('address_2', '');
		$user->suburb = $request->get('suburb', '');
		$user->postcode = $request->get('postcode', '');
		$user->state = $request->get('state', '');
		$user->timezone = $request->get('timezone', '');

		if ($request->password != '') {
			$user->password = bcrypt($request->password);
		}

		$user->save();
	}

}

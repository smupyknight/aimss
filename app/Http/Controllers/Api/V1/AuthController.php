<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use App\Device;
use App\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Services\AuthService;

class AuthController extends Controller
{

	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email'    => 'required',
			'password' => 'required',
		]);

		$user = User::whereEmail($request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			abort(401);
		}

		if (!$user->api_token) {
			$user->api_token = md5(microtime());
			$user->save();
		}

		return response()->json(['token' => $user->api_token]);
	}

	public function postForgotPassword(Request $request)
	{
		$authService = new AuthService;
		$result = $authService->forgotPassword($request);

		return response()->json($result);
	}

	public function postResetPassword(Request $request)
	{
		$authService = new AuthService;
		$result = $authService->resetUserPassword($request);

		return response()->json($result);
	}

	public function postSignup(Request $request)
	{
		$this->validate($request, [
			'first_name'            => 'required',
			'last_name'             => 'required',
			'email'                 => 'required|email|unique:users,email',
			'password'              => 'required|confirmed|min:6',
			'timezone'              => 'required|timezone',
			'account_type'          => 'required|in:Scrutineer,Organizer,Crew,Medical,Spectator',
			'suburb'                => 'required_with:address_1',
			'postcode'              => 'required_with:address_1',
			'state'                 => 'required_with:address_1',
			'identification'        => 'array',
			'identification.*.file' => 'image',
		]);

		$user = new User;
		$user->first_name = $request->get('first_name', '');
		$user->last_name = $request->get('last_name', '');
		$user->is_subscribed = $request->get('subscribed', '');
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->phone = $request->get('phone', '');
		$user->type = $request->get('account_type', '');
		$user->address_1 = $request->get('address_1', '');
		$user->address_2 = $request->get('address_2', '');
		$user->suburb = $request->get('suburb', '');
		$user->postcode = $request->get('postcode', '');
		$user->state = $request->get('state', '');
		$user->timezone = $request->get('timezone', '');
		$user->save();

		$files_uploaded = $request->allFiles();

		foreach ($request->get('identification', []) as $index => $fields) {
			$image = $user->images()->create([
				'type' => $fields['type'],
				'identification_number' => $fields['identification_number'],
			]);

			if (isset($files_uploaded["identification.$index.file"]))
				$image->setImage($files_uploaded["identification.$index.file"]);
		}
	}

}

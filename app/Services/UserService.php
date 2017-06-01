<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Carbon\Carbon;
use App\Exceptions\ServiceValidationException;
use App\UserImage;
use App\Invitation;
use Mail;

class UserService extends Service
{

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return user object
	 */
	public function create(Request $request)
	{
		$this->validate($request, [
			'first_name'            => 'required|max:255',
			'last_name'             => 'required|max:255',
			'email'                 => 'required|email|max:255|unique:users,email',
			'phone'                 => 'required',
			'type'                  => 'required',
			'password'              => 'required|min:6|confirmed',
			'password_confirmation' => 'required',
			'timezone'              => 'required',
		]);

		$user = new User;
		$user->first_name = $request->get('first_name', '');
		$user->last_name = $request->get('last_name', '');
		$user->email = $request->get('email', '');
		$user->phone = $request->get('phone', '');
		$user->type = $request->get('type', '');
		$user->is_subscribed = $request->get('subscribed', '');
		$user->address_1 = $request->get('address_1', '');
		$user->address_2 = $request->get('address_2', '');
		$user->suburb = $request->get('suburb', '');
		$user->postcode = $request->get('postcode', '');
		$user->state = $request->get('state', '');
		$user->timezone = $request->get('timezone', '');
		$user->password = bcrypt($request->get('password', ''));
		$user->save();

		$data = [
			'user' => $user
		];

		Mail::send('emails.welcome', $data, function ($mail) use ($user) {
			$mail->from(env('MAIL_FROM'));
			$mail->to($user->email);
			$mail->subject('Welcome to AIMSS portal');
		});

		return $user;
	}

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return user object
	 */
	public function edit(Request $request , $user_id)
	{
		$this->validate($request, [
			'first_name'            => 'required|max:255',
			'last_name'             => 'required|max:255',
			'email'                 => 'required|email|max:255|unique:users,email,'.$user_id,
			'phone'                 => 'required',
			'type'                  => 'required',
			'password'              => 'confirmed',
			'password_confirmation' => '',
			'timezone'              => 'required',
		]);

		$user = User::find($user_id);
		$user->first_name = $request->get('first_name', '');
		$user->last_name = $request->get('last_name', '');
		$user->email = $request->get('email', '');
		$user->phone = $request->get('phone', '');
		$user->type = $request->get('type', '');
		$user->is_subscribed = $request->get('subscribed', '');
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

		return $user;
	}

}

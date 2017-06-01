<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\User;

class AuthService extends Service
{

	/**
	 * Create forgot password token and send email
	 * @param  Request $request
	 * @return array
	 */
	public function forgotPassword(Request $request)
	{
		$this->validate($request, [
				'email' => 'required|email',
			]);

		$response = Password::sendResetLink(
			$request->only('email'), function (Message $message) {
				$message->subject('Your Password Reset Link');
			}
		);
		switch ($response) {
			case Password::RESET_LINK_SENT:
				return ['status' => trans($response)];

			case Password::INVALID_USER:
			default:
				return ['error' => trans($response)];
		}
	}

	/**
	 * Reset password for user
	 * @param  Request $request
	 * @return array
	 */
	public function resetUserPassword(Request $request)
	{
		$this->validate($request, [
				'token' => 'required',
				'email' => 'required|email',
				'password' => 'required|confirmed|min:6',
			]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			$this->resetPassword($user, $password);
		});

		switch ($response) {
			case Password::PASSWORD_RESET:
				return ['status' => trans($response)];

			default:
				return ['email' => trans($response)];
		}
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		$user->forceFill([
			'password' => bcrypt($password),
			'remember_token' => Str::random(60),
		])->save();
	}

}

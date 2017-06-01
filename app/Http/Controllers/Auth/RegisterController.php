<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Mail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/dashboard';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'first_name' => 'required|max:255',
			'last_name'  => 'required|max:255',
			'email'      => 'required|email|max:255|unique:users',
			'password'   => 'required|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		$user = User::create([
			'first_name'    => $data['first_name'],
			'last_name'     => $data['last_name'],
			'email'         => $data['email'],
			'password'      => bcrypt($data['password']),
			'is_subscribed' => '0',
		]);

		$this->sendWelcomeEmail($user);

		return $user;
	}

	/**
	* Send Welcome Email.
	*
	* @param  array  $data
	* @return User
	*/
	protected function sendWelcomeEmail(User $user)
	{
		$data = [
			'user' => $user
		];

		Mail::send('emails.welcome', $data, function ($mail) use ($user) {
			$mail->from(env('MAIL_FROM'));
			$mail->to($user->email);
			$mail->subject('Welcome to AIMSS portal');
		});
	}

}

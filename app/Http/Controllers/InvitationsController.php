<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use Auth;
use App\User;

class InvitationsController extends Controller
{

	/**
	 * Display Dashboard
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAccept($token)
	{
		$invitation = Invitation::whereToken($token)->first();

		if (!$invitation) {
			abort(404);
		}

		$user = User::find($invitation->user_id);

		if (!$user) {
			abort(404);
		}

		return view('auth.invitations-accept')
		     ->with('invitation', $invitation)
		     ->with('user', $user);
	}

	public function postAccept(Request $request, $token)
	{
		$invitation = Invitation::whereToken($token)->first();

		if (!$invitation) {
			abort(404);
		}

		if ($request->password != $request->password_confirmation) {
			return back()->withErrors(['password_confirmation' => 'Your passwords did not match.']);
		}

		$user = User::find($invitation->user_id);
		$user->password = Hash::make($request->password);
		$user->save();

		$invitation->delete();

		Auth::login($user);

		return redirect('/login');
	}

}

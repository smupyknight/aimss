<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Storage;
use App\UserImage;
use App\Invitation;

class UsersController extends Controller
{

	/**
	 * Show list of all Users
	 */
	public function getIndex(Request $request)
	{
		return $this->getList($request, 'pending');
	}

	public function getList(Request $request, $status)
	{
		$query = User::where('status', $status);

		$query->where(function($query) use($request) {
			$query->orWhere('first_name', 'like', '%' . $request->search . '%');
			$query->orWhere('last_name', 'like', '%' . $request->search . '%');
			$query->orWhere('email', 'like', '%' . $request->search . '%');
		});

		$users = $query->orderBy('id', 'desc')->paginate(25);

		$num_pending = User::where('status', 'pending')->count();
		$num_invited = User::where('status', 'invited')->count();

		return view('pages.users-list')
		     ->with('users', $users)
		     ->with('status', $status)
		     ->with('num_pending', $num_pending)
		     ->with('num_invited', $num_invited);
	}

	/**
	 * Show create user form
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.users-create');
	}

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$userService = new UserService;
		$user = $userService->create($request);

		return redirect('/users');
	}

	/**
	 * Show view user page
	 * @param  int $user_id
	 * @return view
	 */
	public function getView($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('pages.users-view')
		     ->with('user', $user)
		     ->with('title', 'View User');
	}

	/**
	 * Show edit user page
	 * @param  int $user_id
	 * @return view
	 */
	public function getEdit($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('pages.users-edit')
		     ->with('user', $user)
		     ->with('title', 'Edit User');
	}

	/**
	 * Handle edit user data
	 * @param  Request $request
	 * @param  int  $user_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $user_id)
	{
		$userService = new UserService;
		$user = $userService->edit($request, $user_id);

		return redirect('/users');
	}

	public function getInvite()
	{
		return view('pages.users-invite')
			->with('title', 'Invite User');
	}

	public function postInvite(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'last_name'  => 'required|max:255',
			'email'      => 'required|email|max:255|unique:users',
			'type'       => 'required',
		]);

		$user = new User;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->type = $request->type;
		$user->password = '';
		$user->status = 'invited';
		$user->save();

		$user->invite();

		return redirect('/users');
	}

	public function exportSubscribers()
	{
		$subscribers = User::where('is_subscribed', 1)->get();

		$fp = fopen('php://temp', 'r+');
		fputcsv($fp, ['First Name', 'Last Name', 'Email']);

		foreach ($subscribers as $subscriber) {
			fputcsv($fp, [$subscriber->first_name, $subscriber->last_name, $subscriber->email]);
		}

		rewind($fp);
		$contents = stream_get_contents($fp);
		fclose($fp);

		return response($contents)->header('Content-Disposition', 'attachment; filename="subscribers.csv"');
	}

	public function getReinvite($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('modals.users-reinvite')
			->with('user', $user);
	}

	public function postReinvite($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->invite();
	}

	public function getAccept($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('modals.users-accept')
			->with('user', $user);
	}

	public function postAccept($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->status = 'active';
		$user->save();
	}

	public function getEnable($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('modals.users-enable')
			->with('user', $user);
	}

	public function postEnable($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->status = 'active';
		$user->save();
	}

	public function getDisable($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('modals.users-disable')
			->with('user', $user);
	}

	public function postDisable($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->status = 'disabled';
		$user->save();
	}

	public function getDelete($user_id)
	{
		$user = User::whereIn('status', ['pending','invited'])->findOrFail($user_id);

		return view('modals.users-delete')
			->with('user', $user);
	}

	public function postDelete($user_id)
	{
		$user = User::whereIn('status', ['pending','invited'])->findOrFail($user_id);
		$user->invitations()->delete();

		// Images must be deleted individually - the delete() function removes
		// the file from disk.
		foreach ($user->images as $image) {
			$image->delete();
		}

		$user->delete();
	}

}

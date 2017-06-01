<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mail;

class User extends Authenticatable
{
	use Notifiable;

	protected $guarded = [];

	/**
	 * Convenience function to return the user's full name.
	 */
	public function name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * Sends or resends an invitation to the user.
	 */
	public function invite()
	{
		$invitation = $this->invitations()->first();

		if (!$invitation) {
			$invitation = $this->invitations()->create([
				'token' => substr(md5(microtime()), 0, 10),
			]);
		}

		$invitation->touch();

		$data = [
			'user'       => $this,
			'invitation' => $invitation,
		];

		Mail::send('emails.invitations-email', $data, function ($mail) {
			$mail->from(env('MAIL_FROM'));
			$mail->to($this->email);
			$mail->subject('Invitation to join AIMSS portal');
		});
	}

	public function isAdmin()
	{
		return $this->type == 'Admin';
	}

	public function isScrutineer()
	{
		return $this->type == 'Scrutineer';
	}

	public function isOrganizer()
	{
		return $this->type == 'Organizer';
	}

	public function isCrew()
	{
		return $this->type == 'Crew';
	}

	public function isMedical()
	{
		return $this->type == 'Medical';
	}

	public function isSpectator()
	{
		return $this->type == 'Spectator';
	}

	public function images()
	{
		return $this->hasMany('App\UserImage');
	}

	public function invitations()
	{
		return $this->hasMany('App\Invitation');
	}

}

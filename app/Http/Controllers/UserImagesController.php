<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\UserImage;

class UserImagesController extends Controller
{

	/**
	 * Show view user page
	 * @param  int $user_id
	 * @return view
	 */
	public function getView($filename)
	{
		$image = UserImage::where('file', $filename)->firstOrFail();

		if ($image->user_id != $this->user->id && !$this->user->isAdmin()) {
			abort(404);
		}

		$contents = Storage::disk('public')->get('/user_images/' . $image->file);
		$content_type = Storage::disk('public')->mimeType('/user_images/' . $image->file);

		return response($contents, 200)
			->header('Content-type', $content_type);
	}

}

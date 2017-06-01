<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserImage;

class UserImagesController extends Controller
{

	/**
	 * Retrieve user's images.
	 */
	public function getIndex(Request $request)
	{
		$images = [];

		foreach ($this->user->images as $image) {
			$images[] = [
				'id'                    => $image->id,
				'name'                  => $image->name,
				'type'                  => $image->type,
				'identification_number' => $image->identification_number,
				'url'                   => $image->getUrl(),
				'created_at'            => $image->created_at->setTimezone($this->user->timezone)->format('c'),
			];
		}

		return response()->json($images);
	}

	/**
	 * Upload an image.
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'file' => 'required|image',
		]);

		$image = $this->user->images()->create([
			'name' => $request->file('file')->getClientOriginalName(),
		]);

		$image->setImage($request->file('file'));

		return response()->json(['image_id' => $image->id]);
	}

	/**
	 * Delete an image.
	 */
	public function postDelete(Request $request)
	{
		$this->validate($request, [
			'image_id' => 'required|numeric',
		]);

		$image = UserImage::findOrFail($request->image_id);

		$image->delete();
	}

}

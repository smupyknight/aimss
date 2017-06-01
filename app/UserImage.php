<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;

class UserImage extends Model
{

	protected $guarded = [];

	public function setImage(UploadedFile $file)
	{
		if ($this->file) {
			Storage::disk('public')->delete('/user_images/' . $this->file);
		}

		$this->file = $this->id . '-' . $file->getClientOriginalName();

		Storage::disk('public')->putFileAs('/user_images/', $file, $this->file);

		$this->save();
	}

	public function delete()
	{
		if ($this->file) {
			Storage::disk('public')->delete('/user_images/' . $this->file);
		}

		parent::delete();
	}

	/**
	 * Web URL to the image.
	 */
	public function getUrl()
	{
		return url('/storage/user_images/' . $this->file);
	}

}

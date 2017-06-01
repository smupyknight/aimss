<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class FormQuestion extends Model
{

	use SoftDeletes;

	protected $guarded = [];

	public function setReferenceImage($file = null)
	{
		$this->removeReferenceImage();

		$filename = $this->id . '-' . $file->getClientOriginalName();

		Storage::disk('public')->put('question-reference-images/' . $filename, file_get_contents($file));

		$this->reference_image = $filename;
		$this->save();
	}

	public function removeReferenceImage()
	{
		if (!$this->reference_image) {
			return;
		}

		Storage::disk('public')->delete('question-reference-images/' . $this->reference_image);

		$this->reference_image = '';
		$this->save();
	}

	public function conditionalQuestion()
	{
		return $this->belongsTo('App\FormQuestion', 'conditional_question_id');
	}

}

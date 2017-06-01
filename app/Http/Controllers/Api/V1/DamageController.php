<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Damage;
use App\FormSubmission;
use Storage;

class DamageController extends Controller
{

	public function getList(Request $request, $submission_id)
	{
		$submission = FormSubmission::findOrFail($submission_id);

		$results = [];

		foreach ($submission->damages as $damage) {
			$results[] = [
				'id'   => $damage->id,
				'type' => $damage->type,
				'url'  => url(Storage::disk('public')->url('/damage_images/' . $damage->filename)),
			];
		}

		return $results;
	}

	public function postCreate(Request $request, $submission_id)
	{
		$submission = FormSubmission::findOrFail($submission_id);

		$this->validate($request, [
			'file' => 'required|image',
			'type' => 'required|in:Left,Right,Front,Back,Top',
		]);

		$filename = substr(md5(microtime()), 0, 10) . '.' . $request->file('file')->getClientOriginalExtension();

		Storage::disk('public')->putFileAs('/damage_images/', $request->file('file'), $filename);

		$damage                = new Damage;
		$damage->submission_id = $submission->id;
		$damage->filename      = $filename;
		$damage->type          = $request->type;
		$damage->save();
	}

}

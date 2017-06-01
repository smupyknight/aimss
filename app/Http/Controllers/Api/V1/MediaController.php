<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\FormSubmission;
use App\SubmissionMedia;
use Storage;

class MediaController extends Controller
{

	public function postCreate(Request $request, $submission_id)
	{
		$submission = FormSubmission::findOrFail($submission_id);

		$this->validate($request, [
			'file' => 'required',
			'type' => 'required|in:Image,Video',
		]);

		$filename = substr(md5(microtime()), 0, 10) . '.' . $request->file('file')->getClientOriginalExtension();

		Storage::disk('public')->putFileAs('/submission_media/', $request->file('file'), $filename);

		$damage                = new SubmissionMedia;
		$damage->submission_id = $submission->id;
		$damage->filename      = $filename;
		$damage->type          = $request->type;
		$damage->save();
	}

	public function getIndex($submission_id)
	{
		$submission = FormSubmission::findOrFail($submission_id);

		$data = [];

		foreach ($submission->media as $media) {
			$data[] = [
				'id'   => $media->id,
				'type' => $media->type,
				'url'  => url(Storage::disk('public')->url('/submission_media/' . $media->filename)),
			];
		}

		return response()->json($data);
	}

}

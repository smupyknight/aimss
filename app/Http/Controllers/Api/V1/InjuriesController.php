<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Exceptions\ServiceValidationException;
use App\FormSubmission;
use App\Injury;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InjuriesController extends Controller
{

	public function getList($submission_id)
	{
		$submission = FormSubmission::where('user_id', $this->user->id)->findOrFail($submission_id);

		$results = [];

		foreach ($submission->injuries as $injury) {
			$results[] = [
				'id'   => $injury->id,
				'name' => $injury->first_name . ' ' . $injury->last_name,
			];
		}

		return $results;
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'submission_id'                 => 'required',
			'dob'                           => 'date_format:Y-m-d',
			'head_eye_which'                => 'in:L,R,B',
			'extremities_buttocks_which'    => 'in:L,R,B',
			'extremities_upperarm_which'    => 'in:L,R,B',
			'extremities_lowerarm_which'    => 'in:L,R,B',
			'extremities_hand_injury_which' => 'in:L,R,B',
			'extremities_upperleg_which'    => 'in:L,R,B',
			'extremities_lowerleg_which'    => 'in:L,R,B',
			'extremities_foot_which'        => 'in:L,R,B',
			'responder_doctor_time'         => 'date_format:' . Carbon::ISO8601,
			'responder_paramedic_time'      => 'date_format:' . Carbon::ISO8601,
			'responder_official_time'       => 'date_format:' . Carbon::ISO8601,
			'responder_competitor_time'     => 'date_format:' . Carbon::ISO8601,
			'responder_spectator_time'      => 'date_format:' . Carbon::ISO8601,
			'responder_other1_time'         => 'date_format:' . Carbon::ISO8601,
			'responder_other2_time'         => 'date_format:' . Carbon::ISO8601,
			'resource_medicalcar_time'      => 'date_format:' . Carbon::ISO8601,
			'resource_extricationunit_time' => 'date_format:' . Carbon::ISO8601,
			'resource_ambulance_time'       => 'date_format:' . Carbon::ISO8601,
			'resource_cuttingvehicle_time'  => 'date_format:' . Carbon::ISO8601,
			'resource_fireunit_time'        => 'date_format:' . Carbon::ISO8601,
			'resource_helicopter_time'      => 'date_format:' . Carbon::ISO8601,
			'resource_other1_time'          => 'date_format:' . Carbon::ISO8601,
			'resource_other2_time'          => 'date_format:' . Carbon::ISO8601,
			'transfer_medicalcentre_time'   => 'date_format:' . Carbon::ISO8601,
			'transfer_hospital_time'        => 'date_format:' . Carbon::ISO8601,
		]);

		$fields                  = $this->buildFields($request);
		$fields_str = \GuzzleHttp\json_encode($fields);

		$fields['submission_id'] = $request->submission_id;

		$injury = Injury::create($fields);

		if ($request->spinepelvis_vertebra) {
			$injury->setVertebra((array) $request->spinepelvis_injured_vertebra);
			$injury->save();
		}
	}

	public function postEdit(Request $request, $injury_id)
	{
		$this->validate($request, [
			'dob'                           => 'date_format:Y-m-d',
			'head_eye_which'                => 'in:L,R,B',
			'extremities_buttocks_which'    => 'in:L,R,B',
			'extremities_upperarm_which'    => 'in:L,R,B',
			'extremities_lowerarm_which'    => 'in:L,R,B',
			'extremities_hand_injury_which' => 'in:L,R,B',
			'extremities_upperleg_which'    => 'in:L,R,B',
			'extremities_lowerleg_which'    => 'in:L,R,B',
			'extremities_foot_which'        => 'in:L,R,B',
			'responder_doctor_time'         => 'date_format:' . Carbon::ISO8601,
			'responder_paramedic_time'      => 'date_format:' . Carbon::ISO8601,
			'responder_official_time'       => 'date_format:' . Carbon::ISO8601,
			'responder_competitor_time'     => 'date_format:' . Carbon::ISO8601,
			'responder_spectator_time'      => 'date_format:' . Carbon::ISO8601,
			'responder_other1_time'         => 'date_format:' . Carbon::ISO8601,
			'responder_other2_time'         => 'date_format:' . Carbon::ISO8601,
			'resource_medicalcar_time'      => 'date_format:' . Carbon::ISO8601,
			'resource_extricationunit_time' => 'date_format:' . Carbon::ISO8601,
			'resource_ambulance_time'       => 'date_format:' . Carbon::ISO8601,
			'resource_cuttingvehicle_time'  => 'date_format:' . Carbon::ISO8601,
			'resource_fireunit_time'        => 'date_format:' . Carbon::ISO8601,
			'resource_helicopter_time'      => 'date_format:' . Carbon::ISO8601,
			'resource_other1_time'          => 'date_format:' . Carbon::ISO8601,
			'resource_other2_time'          => 'date_format:' . Carbon::ISO8601,
			'transfer_medicalcentre_time'   => 'date_format:' . Carbon::ISO8601,
			'transfer_hospital_time'        => 'date_format:' . Carbon::ISO8601,
		]);

		$injury = Injury::findOrFail($injury_id);

		if ($injury->submission->user_id != $this->user->id) {
			abort(403);
		}

		if (!$this->user->isMedical() && $injury->created_at < new Carbon('7 days ago')) {
			throw new ServiceValidationException('Injuries cannot be edited once they are 7 days old.');
		}

		$injury->update($this->buildFields($request));

		$injury->setVertebra((array) $request->spinepelvis_injured_vertebra);
		$injury->save();
	}

	public function postDelete(Request $request, $injury_id)
	{
		$injury = Injury::findOrFail($injury_id);

		if ($injury->submission->user_id != $this->user->id) {
			abort(403);
		}

		if (!$this->user->isMedical() && $injury->created_at < new Carbon('7 days ago')) {
			throw new ServiceValidationException('Injuries cannot be deleted once they are 7 days old.');
		}

		$injury->delete();
	}

	public function getView($injury_id)
	{
		$injury = Injury::findOrFail($injury_id);

		if ($injury->submission->user_id != $this->user->id) {
			abort(403);
		}

		//
		$fields = collect($injury->toArray())
			->except(['submission', 'created_at', 'updated_at'])
			->transform(function ($value, $key) {
				// Convert datetime fields to ISO 8601
				return (strpos($key, '_time') !== false) && $value ? (new Carbon($value))->format('c') : $value;
			});

		return response()->json($fields);
	}

	/**
	 * Converts the Request fields into database field values.
	 */
	private function buildFields(Request $request)
	{
		return [
			'first_name'                           => $request->get('first_name', ''),
			'last_name'                            => $request->get('last_name', ''),
			'dob'                                  => $request->dob,
			'is_accident'                          => (bool) $request->is_accident,
			'is_caused_by_accident'                => (bool) $request->is_caused_by_accident,
			'is_admitted_to_hospital'              => (bool) $request->is_admitted_to_hospital,
			'is_paralysed_or_ventilated'           => (bool) $request->is_paralysed_or_ventilated,
			'used_drugs'                           => (bool) $request->used_drugs,
			'drugs'                                => $request->get('drugs', ''),
			'is_head_injured'                      => (bool) $request->is_head_injured,
			'is_spinepelvis_injured'               => (bool) $request->is_spinepelvis_injured,
			'is_chest_injured'                     => (bool) $request->is_chest_injured,
			'is_abdomengenitals_injured'           => (bool) $request->is_abdomengenitals_injured,
			'is_extremities_injured'               => (bool) $request->is_extremities_injured,
			'head_skull_injury_type'               => $request->get('head_skull_injury_type', ''),
			'head_brain_injury_type'               => $request->get('head_brain_injury_type', ''),
			'head_face_injury_type'                => $request->get('head_face_injury_type', ''),
			'head_eye_injury_type'                 => $request->get('head_eye_injury_type', ''),
			'head_tongue_injury_type'              => $request->get('head_tongue_injury_type', ''),
			'head_teeth_injury_type'               => $request->get('head_teeth_injury_type', ''),
			'head_other_injury_type'               => $request->get('head_other_injury_type', ''),
			'head_eye_which'                       => $request->get('head_eye_which', ''),
			'spinepelvis_cervicalbone_injury_type' => $request->get('spinepelvis_cervicalbone_injury_type', ''),
			'spinepelvis_cervicalcord_injury_type' => $request->get('spinepelvis_cervicalcord_injury_type', ''),
			'spinepelvis_thoraticbone_injury_type' => $request->get('spinepelvis_thoraticbone_injury_type', ''),
			'spinepelvis_thoraticcord_injury_type' => $request->get('spinepelvis_thoraticcord_injury_type', ''),
			'spinepelvis_lumbarbone_injury_type'   => $request->get('spinepelvis_lumbarbone_injury_type', ''),
			'spinepelvis_lumbarcord_injury_type'   => $request->get('spinepelvis_lumbarcord_injury_type', ''),
			'spinepelvis_pelvis_injury_type'       => $request->get('spinepelvis_pelvis_injury_type', ''),
			'spinepelvis_other_injury_type'        => $request->get('spinepelvis_other_injury_type', ''),
			'chest_wall_injury_type'               => $request->get('chest_wall_injury_type', ''),
			'chest_internal_injury_type'           => $request->get('chest_internal_injury_type', ''),
			'chest_lungs_injury_type'              => $request->get('chest_lungs_injury_type', ''),
			'chest_cardiac_injury_type'            => $request->get('chest_cardiac_injury_type', ''),
			'chest_is_cardiacarrest'               => (bool) $request->chest_is_cardiacarrest,
			'chest_is_cpr_given'                   => (bool) $request->chest_is_cpr_given,
			'chest_other_injury_type'              => $request->get('chest_other_injury_type', ''),
			'abdomen_injury_type'                  => $request->get('abdomen_injury_type', ''),
			'genitals_injury_type'                 => $request->get('genitals_injury_type', ''),
			'extremities_buttocks_injury_type'     => $request->get('extremities_buttocks_injury_type', ''),
			'extremities_upperarm_injury_type'     => $request->get('extremities_upperarm_injury_type', ''),
			'extremities_lowerarm_injury_type'     => $request->get('extremities_lowerarm_injury_type', ''),
			'extremities_hand_injury_type'         => $request->get('extremities_hand_injury_type', ''),
			'extremities_upperleg_injury_type'     => $request->get('extremities_upperleg_injury_type', ''),
			'extremities_lowerleg_injury_type'     => $request->get('extremities_lowerleg_injury_type', ''),
			'extremities_foot_injury_type'         => $request->get('extremities_foot_injury_type', ''),
			'extremities_other_injury_type'        => $request->get('extremities_other_injury_type', ''),
			'extremities_buttocks_which'           => $request->get('extremities_buttocks_which', ''),
			'extremities_upperarm_which'           => $request->get('extremities_upperarm_which', ''),
			'extremities_lowerarm_which'           => $request->get('extremities_lowerarm_which', ''),
			'extremities_hand_injury_which'        => $request->get('extremities_hand_injury_which', ''),
			'extremities_upperleg_which'           => $request->get('extremities_upperleg_which', ''),
			'extremities_lowerleg_which'           => $request->get('extremities_lowerleg_which', ''),
			'extremities_foot_which'               => $request->get('extremities_foot_which', ''),
			'responder_doctor_time'                => $request->responder_doctor_time ? new Carbon($request->responder_doctor_time) : null,
			'responder_paramedic_time'             => $request->responder_paramedic_time ? new Carbon($request->responder_paramedic_time) : null,
			'responder_official_time'              => $request->responder_official_time ? new Carbon($request->responder_official_time) : null,
			'responder_competitor_time'            => $request->responder_competitor_time ? new Carbon($request->responder_competitor_time) : null,
			'responder_spectator_time'             => $request->responder_spectator_time ? new Carbon($request->responder_spectator_time) : null,
			'responder_other1_name'                => $request->get('responder_other1_name', ''),
			'responder_other1_time'                => $request->responder_other1_time ? new Carbon($request->responder_other1_time) : null,
			'responder_other2_name'                => $request->get('responder_other2_name', ''),
			'responder_other2_time'                => $request->responder_other2_time ? new Carbon($request->responder_other2_time) : null,
			'resource_medicalcar_time'             => $request->resource_medicalcar_time ? new Carbon($request->resource_medicalcar_time) : null,
			'resource_extricationunit_time'        => $request->resource_extricationunit_time ? new Carbon($request->resource_extricationunit_time) : null,
			'resource_ambulance_time'              => $request->resource_ambulance_time ? new Carbon($request->resource_ambulance_time) : null,
			'resource_cuttingvehicle_time'         => $request->resource_cuttingvehicle_time ? new Carbon($request->resource_cuttingvehicle_time) : null,
			'resource_fireunit_time'               => $request->resource_fireunit_time ? new Carbon($request->resource_fireunit_time) : null,
			'resource_helicopter_time'             => $request->resource_helicopter_time ? new Carbon($request->resource_helicopter_time) : null,
			'resource_other1_name'                 => $request->get('resource_other1_name', ''),
			'resource_other1_time'                 => $request->resource_other1_time ? new Carbon($request->resource_other1_time) : null,
			'resource_other2_name'                 => $request->get('resource_other2_name', ''),
			'resource_other2_time'                 => $request->resource_other2_time ? new Carbon($request->resource_other2_time) : null,
			'comascale_initial'                    => (int) $request->comascale_initial,
			'comascale_transfer'                   => (int) $request->comascale_transfer,
			'extrication_is_self'                  => (bool) $request->extrication_is_self,
			'extrication_is_emergency'             => (bool) $request->extrication_is_emergency,
			'extrication_is_planned'               => (bool) $request->extrication_is_planned,
			'extrication_is_team'                  => (bool) $request->extrication_is_team,
			'extrication_is_rescueworkers'         => (bool) $request->extrication_is_rescueworkers,
			'extrication_person_other'             => $request->get('extrication_person_other', ''),
			'extrication_is_windscreen_removed'    => (bool) $request->extrication_is_windscreen_removed,
			'extrication_is_steeringwheel_removed' => (bool) $request->extrication_is_steeringwheel_removed,
			'extrication_is_cutting_required'      => (bool) $request->extrication_is_cutting_required,
			'extrication_cutting_details'          => $request->get('extrication_cutting_details', ''),
			'extrication_is_splints'               => (bool) $request->extrication_is_splints,
			'extrication_splints_details'          => $request->get('extrication_splints_details', ''),
			'transfer_medicalcentre_method'        => $request->get('transfer_medicalcentre_method', ''),
			'transfer_medicalcentre_time'          => $request->transfer_medicalcentre_time ? new Carbon($request->transfer_medicalcentre_time) : null,
			'transfer_hospital_method'             => $request->get('transfer_hospital_method', ''),
			'transfer_hospital_time'               => $request->transfer_hospital_time ? new Carbon($request->transfer_hospital_time) : null,
			'notes'                                => (string) $request->notes,
		];
	}

}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Injuries extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('injuries', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('submission_id')->unsigned()->index();
			$table->string('first_name');
			$table->string('last_name');
			$table->date('dob')->nullable();
			$table->tinyInteger('is_accident');
			$table->tinyInteger('is_caused_by_accident');
			$table->tinyInteger('is_admitted_to_hospital');
			$table->tinyInteger('is_paralysed_or_ventilated');
			$table->tinyInteger('used_drugs');
			$table->text('drugs');

			$table->tinyInteger('is_head_injured');
			$table->tinyInteger('is_spinepelvis_injured');
			$table->tinyInteger('is_chest_injured');
			$table->tinyInteger('is_abdomengenitals_injured');
			$table->tinyInteger('is_extremities_injured');

			$table->string('head_skull_injury_type');
			$table->string('head_brain_injury_type');
			$table->string('head_face_injury_type');
			$table->string('head_eye_injury_type');
			$table->char('head_eye_which', 1);
			$table->string('head_tongue_injury_type');
			$table->string('head_teeth_injury_type');
			$table->string('head_other_injury_type');

			$table->integer('spinepelvis_injured_vertebra')->unsigned();
			$table->string('spinepelvis_cervicalbone_injury_type');
			$table->string('spinepelvis_cervicalcord_injury_type');
			$table->string('spinepelvis_thoraticbone_injury_type');
			$table->string('spinepelvis_thoraticcord_injury_type');
			$table->string('spinepelvis_lumbarbone_injury_type');
			$table->string('spinepelvis_lumbarcord_injury_type');
			$table->string('spinepelvis_pelvis_injury_type');
			$table->string('spinepelvis_other_injury_type');

			$table->string('chest_wall_injury_type');
			$table->string('chest_internal_injury_type');
			$table->string('chest_lungs_injury_type');
			$table->string('chest_cardiac_injury_type');
			$table->tinyInteger('chest_is_cardiacarrest');
			$table->tinyInteger('chest_is_cpr_given');
			$table->string('chest_other_injury_type');

			$table->string('abdomen_injury_type');
			$table->string('genitals_injury_type');

			$table->string('extremities_buttocks_injury_type');
			$table->char('extremities_buttocks_which', 1);
			$table->string('extremities_upperarm_injury_type');
			$table->char('extremities_upperarm_which', 1);
			$table->string('extremities_lowerarm_injury_type');
			$table->char('extremities_lowerarm_which', 1);
			$table->string('extremities_hand_injury_type');
			$table->char('extremities_hand_injury_which', 1);
			$table->string('extremities_upperleg_injury_type');
			$table->char('extremities_upperleg_which', 1);
			$table->string('extremities_lowerleg_injury_type');
			$table->char('extremities_lowerleg_which', 1);
			$table->string('extremities_foot_injury_type');
			$table->char('extremities_foot_which', 1);
			$table->string('extremities_other_injury_type');

			$table->datetime('responder_doctor_time')->nullable();
			$table->datetime('responder_paramedic_time')->nullable();
			$table->datetime('responder_official_time')->nullable();
			$table->datetime('responder_competitor_time')->nullable();
			$table->datetime('responder_spectator_time')->nullable();
			$table->string('responder_other1_name');
			$table->datetime('responder_other1_time')->nullable();
			$table->string('responder_other2_name');
			$table->datetime('responder_other2_time')->nullable();

			$table->datetime('resource_medicalcar_time')->nullable();
			$table->datetime('resource_extricationunit_time')->nullable();
			$table->datetime('resource_ambulance_time')->nullable();
			$table->datetime('resource_cuttingvehicle_time')->nullable();
			$table->datetime('resource_fireunit_time')->nullable();
			$table->datetime('resource_helicopter_time')->nullable();
			$table->string('resource_other1_name');
			$table->datetime('resource_other1_time')->nullable();
			$table->string('resource_other2_name');
			$table->datetime('resource_other2_time')->nullable();

			$table->integer('comascale_initial');
			$table->integer('comascale_transfer');

			$table->tinyInteger('extrication_is_self');
			$table->tinyInteger('extrication_is_emergency');
			$table->tinyInteger('extrication_is_planned');
			$table->tinyInteger('extrication_is_team');
			$table->tinyInteger('extrication_is_rescueworkers');
			$table->string('extrication_person_other');
			$table->tinyInteger('extrication_is_windscreen_removed');
			$table->tinyInteger('extrication_is_steeringwheel_removed');
			$table->tinyInteger('extrication_is_cutting_required');
			$table->string('extrication_cutting_details');
			$table->tinyInteger('extrication_is_splints');
			$table->string('extrication_splints_details');

			$table->string('transfer_medicalcentre_method');
			$table->datetime('transfer_medicalcentre_time')->nullable();
			$table->string('transfer_hospital_method');
			$table->datetime('transfer_hospital_time')->nullable();

			$table->text('notes');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('injuries');
	}

}

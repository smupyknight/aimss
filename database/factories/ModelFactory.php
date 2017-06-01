<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * Factory for basic User
 */
$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
		'first_name'     => $faker->firstName,
		'last_name'      => $faker->lastName,
		'phone'          => '12345678',
		'email'          => $faker->unique()->safeEmail,
		'password'       => bcrypt('TestPassword'),
		'api_token'      => '',
		'type'           => 'Spectator',
		'status'         => 'active',
		'is_subscribed'  => 0,
		'address_1'      => $faker->streetAddress,
		'address_2'      => $faker->address,
		'suburb'         => 'Fakeville',
		'postcode'       => '4000',
		'state'          => 'QLD',
		'timezone'       => 'Australia/Brisbane',
		'remember_token' => str_random(10),
		'created_at'     => '2015-10-28 19:18:44',
		'updated_at'     => '2015-10-28 19:18:44',
	];
});

/**
 * Factory for UserImage
 */
$factory->define(App\UserImage::class, function (Faker\Generator $faker) {
	$user = factory(App\User::class)->create();

	$stub_1       = __DIR__ . '/../../tests/test_stubs/test.jpg';
	$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);
	$file_name    = $user->id . '-' . $image_file_1->getClientOriginalName();
	$image_file_1->storeAs('/public/user_images', $file_name);

	return [
		'user_id'               => $user->id,
		'file'                  => $file_name,
		'type'                  => 'TestType' . str_random(3),
		'identification_number' => 'TestID' . str_random(3),
	];
});

/**
 * Factory for Event
 */
$factory->define(App\Event::class, function (Faker\Generator $faker) {
	$user = factory(App\User::class)->create([
		'type' => 'Admin',
	]);

	return [
		'user_id'                      => $user->id,
		'camms_id'                     => str_random(10),
		'name'                         => 'TestEvent_' . $faker->city . str_random(5),
		'status'                       => 'accepted',
		'type'                         => 'Club',
		'style'                        => 'Pace Noted with Optional Reconnaissance',
		'competition_service'          => 'Gravel',
		'competitor_progression'       => 5,
		'competitor_progression_other' => 'TestProgression' . str_random(4),
		'medical_park_firstaid'        => '1',
		'medical_park_ambulance'       => '1',
		'medical_park_other'           => null,
		'medical_route_firstaid'       => '1',
		'medical_route_ambulance'      => '1',
		'medical_route_other'          => null,
		'spectator'                    => 'Yes',
		'competitive_distance'         => '123',
		'competitor_number'            => '234',
		'location'                     => $faker->city,
		'longitude'                    => $faker->longitude,
		'latitude'                     => $faker->latitude,
		'description'                  => 'TestDescription' . str_random(4),
		'start_date'                   => '2017-04-19 14:00:00',
		'end_date'                     => '2017-04-19 14:00:00',
		'timezone'                     => 'Australia/Sydney',
		'created_at'                   => '2017-04-20 15:23:06',
		'updated_at'                   => '2017-04-20 15:23:06',
	];
});

/**
 * Factory for Event Stage
 */
$factory->define(App\EventStage::class, function (Faker\Generator $faker) {
	$event = factory(App\Event::class)->create();

	return [
		'event_id'     => $event->id,
		'stage_number' => $faker->randomNumber(3),
		'distance'     => $faker->randomNumber(3),
		'fastest_time' => $faker->randomNumber(2),
		'created_at'   => '2017-04-20 15:23:06',
		'updated_at'   => '2017-04-20 15:23:06',
	];
});

/**
 * Factory for Incident
 */
$factory->define(App\Incident::class, function (Faker\Generator $faker) {
	$user  = factory(App\User::class)->create();
	$event = factory(App\Event::class)->create();

	return [
		'user_id'       => $user->id,
		'event_id'      => $event->id,
		'name'          => 'TestIncident_' . str_random(5),
		'status'        => 'open',
		'incident_time' => '2017-04-20 15:23:06',
		'created_at'    => '2017-04-20 15:23:06',
		'updated_at'    => '2017-04-20 15:23:06',
	];
});

/**
 * Factory for FormCategory, Use with loop to bulk add.
 */
$factory->define(App\FormCategory::class, function (Faker\Generator $faker) {
	return [
		'name' => 'TestFormCategory_' . str_random(5),
		'num'  => 1,
	];
});

/**
 * Factory for FormQuestion, Use loop to bulk add, num must be specified for multiple FormCategories.
 */
$factory->define(App\FormQuestion::class, function (Faker\Generator $faker) {
	$category = factory(App\FormCategory::class)->create();
	$user     = factory(App\User::class)->create([
		'type' => 'Admin',
	]);

	return [
		'category_id'                 => $category->id,
		'conditional_question_id'     => null,
		'created_by'                  => $user->id,
		'question'                    => 'TestQuestion_' . str_random(5),
		'type'                        => 'shorttext',
		'options'                     => '',
		'reference_image'             => '',
		'conditional_question_answer' => '',
		'num'                         => 1,
		'show_to_spectator'           => 'required',
		'show_to_crew'                => 'required',
		'show_to_medical'             => 'required',
		'show_to_organiser'           => 'required',
		'show_to_scrutineer'          => 'required',
		'created_at'                  => '2017-04-20 15:23:06',
		'updated_at'                  => '2017-04-20 15:23:06',
	];
});

/**
 * Factory for FormSubmission, Only creates FormSubmission, no FormAnswers.
 */
$factory->define(App\FormSubmission::class, function (Faker\Generator $faker) {
	$user     = factory(App\User::class)->create();
	$incident = factory(App\Incident::class)->create();

	return [
		'incident_id'  => $incident->id,
		'user_id'      => $user->id,
		'is_aggregate' => 0,
		'created_at'   => '2017-04-20 15:23:06',
		'updated_at'   => '2017-04-20 15:23:06',
	];
});

/**
 * Factory for Injury
 */
$factory->define(App\Injury::class, function (Faker\Generator $faker) {
	$submission = factory(App\FormSubmission::class)->create();

	return [
		'submission_id'                        => $submission->id,
		'first_name'                           => 'TestInjuryFirstName' . str_random(3),
		'last_name'                            => 'TestInjuryLastName' . str_random(3),
		'dob'                                  => '2017-12-12',
		'is_accident'                          => false,
		'is_caused_by_accident'                => false,
		'is_admitted_to_hospital'              => false,
		'is_paralysed_or_ventilated'           => false,
		'used_drugs'                           => false,
		'drugs'                                => '',
		'is_head_injured'                      => false,
		'is_spinepelvis_injured'               => false,
		'is_chest_injured'                     => false,
		'is_abdomengenitals_injured'           => false,
		'is_extremities_injured'               => false,
		'head_skull_injury_type'               => '',
		'head_brain_injury_type'               => '',
		'head_face_injury_type'                => '',
		'head_eye_injury_type'                 => '',
		'head_tongue_injury_type'              => '',
		'head_teeth_injury_type'               => '',
		'head_other_injury_type'               => '',
		'head_eye_which'                       => 'R',
		'spinepelvis_cervicalbone_injury_type' => '',
		'spinepelvis_cervicalcord_injury_type' => '',
		'spinepelvis_thoraticbone_injury_type' => '',
		'spinepelvis_thoraticcord_injury_type' => '',
		'spinepelvis_lumbarbone_injury_type'   => '',
		'spinepelvis_lumbarcord_injury_type'   => '',
		'spinepelvis_pelvis_injury_type'       => '',
		'spinepelvis_other_injury_type'        => '',
		'chest_wall_injury_type'               => '',
		'chest_internal_injury_type'           => '',
		'chest_lungs_injury_type'              => '',
		'chest_cardiac_injury_type'            => '',
		'chest_is_cardiacarrest'               => false,
		'chest_is_cpr_given'                   => false,
		'chest_other_injury_type'              => '',
		'abdomen_injury_type'                  => '',
		'genitals_injury_type'                 => '',
		'extremities_buttocks_injury_type'     => '',
		'extremities_upperarm_injury_type'     => '',
		'extremities_lowerarm_injury_type'     => '',
		'extremities_hand_injury_type'         => '',
		'extremities_upperleg_injury_type'     => '',
		'extremities_lowerleg_injury_type'     => '',
		'extremities_foot_injury_type'         => '',
		'extremities_other_injury_type'        => '',
		'extremities_buttocks_which'           => 'L',
		'extremities_upperarm_which'           => 'B',
		'extremities_lowerarm_which'           => 'R',
		'extremities_hand_injury_which'        => 'L',
		'extremities_upperleg_which'           => 'B',
		'extremities_lowerleg_which'           => 'R',
		'extremities_foot_which'               => 'L',
		'responder_doctor_time'                => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_paramedic_time'             => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_official_time'              => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_competitor_time'            => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_spectator_time'             => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_other1_name'                => '',
		'responder_other1_time'                => Carbon::now()->addDays(1)->toIso8601String(),
		'responder_other2_name'                => '',
		'responder_other2_time'                => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_medicalcar_time'             => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_extricationunit_time'        => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_ambulance_time'              => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_cuttingvehicle_time'         => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_fireunit_time'               => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_helicopter_time'             => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_other1_name'                 => '',
		'resource_other1_time'                 => Carbon::now()->addDays(1)->toIso8601String(),
		'resource_other2_name'                 => '',
		'resource_other2_time'                 => Carbon::now()->addDays(1)->toIso8601String(),
		'comascale_initial'                    => 0,
		'comascale_transfer'                   => 0,
		'extrication_is_self'                  => false,
		'extrication_is_emergency'             => false,
		'extrication_is_planned'               => false,
		'extrication_is_team'                  => false,
		'extrication_is_rescueworkers'         => false,
		'extrication_person_other'             => '',
		'extrication_is_windscreen_removed'    => false,
		'extrication_is_steeringwheel_removed' => false,
		'extrication_is_cutting_required'      => false,
		'extrication_cutting_details'          => '',
		'extrication_is_splints'               => false,
		'extrication_splints_details'          => '',
		'transfer_medicalcentre_method'        => '',
		'transfer_medicalcentre_time'          => Carbon::now()->addDays(1)->toIso8601String(),
		'transfer_hospital_method'             => '',
		'transfer_hospital_time'               => Carbon::now()->addDays(1)->toIso8601String(),
		'notes'                                => 'Test Notes' . str_random(10),
	];
});

/**
 * Factory for Damage
 */
$factory->define(App\Damage::class, function (Faker\Generator $faker) {
	$submission = factory(App\FormSubmission::class)->create();

	$stub_1       = __DIR__ . '/../../tests/test_stubs/test.jpg';
	$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);
	$file         = substr(md5(microtime()), 0, 10) . '.' . $image_file_1->getClientOriginalExtension();
	$image_file_1->storeAs('/public/damage_images', $file);

	return [
		'submission_id' => $submission,
		'type'          => 'Left',
		'filename'      => $file,
	];
});

/**
 * Factory for Submission media
 */
$factory->define(App\SubmissionMedia::class, function (Faker\Generator $faker) {
	$submission = factory(App\FormSubmission::class)->create();

	$stub_1       = __DIR__ . '/../../tests/test_stubs/test.jpg';
	$image_file_1 = new UploadedFile($stub_1, 'test.jpg', filesize($stub_1), 'image/jpeg', null, true);
	$file         = substr(md5(microtime()), 0, 10) . '.' . $image_file_1->getClientOriginalExtension();
	$image_file_1->storeAs('/public/submission_media', $file);

	return [
		'submission_id' => $submission,
		'filename'      => $file,
		'type'          => 'Image',
	];
});
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventStage extends Model
{

	protected $guarded = [];

	protected $dates = [
		'created_at',
		'updated_at'
	];

	/**
	 * Returns the fastest time in a human readable format such as
	 * 12:34:56.1234.
	 */
	public function getFastestTimeForHumans()
	{
		$raw = $this->fastest_time;

		$hours = floor($raw / 3600);
		$minutes = floor($raw / 60) % 60;
		$seconds = fmod($raw, 60);

		$string = '';

		if ($hours) {
			return sprintf('%d:%02d:%07.4f', $hours, $minutes, $seconds);
		}

		if ($minutes) {
			return sprintf('%d:%07.4f', $minutes, $seconds);
		}

		return $seconds;
	}

	/**
	 * Converts a human readable time such as 12:34:56.1234 into raw seconds.
	 */
	public static function parseFastestTime($string)
	{
		$parts = explode(':', $string);
		$num_parts = count($parts);

		if ($num_parts >= 3) {
			return $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
		}

		if ($num_parts == 2) {
			return $parts[0] * 60 + $parts[1];
		}

		return (float) $parts[1];
	}

}

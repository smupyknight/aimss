<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Injury extends Model
{
	protected $guarded = [];

	/**
	 * $vertebra is in the format: [
	 *     'c1' => 1,
	 *     'c3' => 1,
	 * ]
	 */
	public function setVertebra(array $vertebra)
	{
		$int = 0;

		$map = [
			'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7',
			't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12',
			'l1', 'l2', 'l3', 'l4', 'l5',
		];

		foreach (array_keys($vertebra) as $item) {
			$index = array_search($item, $map);

			if ($index !== false) {
				$int |= 1 << $index;
			}
		}

		$this->spinepelvis_injured_vertebra = $int;
	}

	/**
	 * Returns injured vertebra in the format: [
	 *     'c1' => 1,
	 *     'c2' => 1,
	 * ]
	 */
	public function getVertebra()
	{
		$array = [];

		$map = [
			'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7',
			't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10', 't11', 't12',
			'l1', 'l2', 'l3', 'l4', 'l5',
		];

		for ($i = 0; $i < 32; $i++) {
			if (($this->spinepelvis_injured_vertebra >> $i) & 1) {
				$array[$map[$i]] = 1;
			}
		}

		return $array;
	}

	public function submission()
	{
		return $this->belongsTo('App\FormSubmission');
	}

}

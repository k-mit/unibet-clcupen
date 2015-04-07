<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateBetRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'bet_team1_1' => 'integer',
			'bet_team1_2' => 'integer',
			'bet_team1_3' => 'integer',
			'bet_team1_4' => 'integer',
			'bet_team1_1' => 'integer',
			'bet_team1_2' => 'integer',
			'bet_team1_3' => 'integer',
			'bet_team1_4' => 'integer',
			'match_id_1'  => 'integer',
			'match_id_2'  => 'integer',
			'match_id_3'  => 'integer',
			'match_id_4'  => 'integer',
			'tiebreaker_1'  => 'integer',
			'tiebreaker_2'  => 'integer',
			'tiebreaker_3'  => 'integer',
			'tiebreaker_4'  => 'integer',
			'shirt_size' => 'max:10',
			'email2' => 'email|max 100'
		];
	}

}

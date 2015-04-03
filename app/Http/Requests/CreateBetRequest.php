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
			'bet_team1_1' => 'required|integer',
			'bet_team1_2' => 'required|integer',
			'bet_team1_3' => 'required|integer',
			'bet_team1_4' => 'required|integer',
			'bet_team1_1' => 'required|integer',
			'bet_team1_2' => 'required|integer',
			'bet_team1_3' => 'required|integer',
			'bet_team1_4' => 'required|integer',
			'match_id_1'  => 'required|integer',
			'match_id_2'  => 'required|integer',
			'match_id_3'  => 'required|integer',
			'match_id_4'  => 'required|integer',
			'tiebreaker'  => 'integer',
			'shirt_size' => 'max:10',
			'email2' => 'email|max 100'
		];
	}

}

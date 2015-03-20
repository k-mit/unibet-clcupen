<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model {

	//
	public function bets(){
		return $this->belongsTo('App\User');
	}

}

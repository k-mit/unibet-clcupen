<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Highscore extends Model {

	protected $fillable = ['user_id', 'score','round'];


	public function user(){
		return $this->belongsTo('App\User');
	}
	public function tiebreaker_result() {
		return $this->hasOne('App\TiebreakerResult', 'round_id', 'round');
	}

}

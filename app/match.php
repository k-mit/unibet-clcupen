<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model {

	protected $dates=['match_time'];

	public function team1() {
		return $this->hasOne('App\Team', 'id', 'team1_id');
	}
	public function team2(){
		return $this->hasOne('App\Team', 'id', 'team2_id');
	}
	public function round(){
		return $this->belongsTo('App\Round');
	}
	public function result(){
		return $this->belongsTo('App\Result');
	}
}

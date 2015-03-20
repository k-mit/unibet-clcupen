<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

	//
	public function results(){
		return $this->hasMany('App\Match','team1_id','id')+$this->hasMany('App\Match','team2_id','id');
	}
}

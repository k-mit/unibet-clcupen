<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

	//
	public function results(){
		return $this->hasMany('app\Match','team1_id','id')+$this->hasMany('app\Match','team2_id','id');
	}
}

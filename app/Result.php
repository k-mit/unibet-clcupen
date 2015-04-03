<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model {

	protected $fillable = ['goals_team1', 'goals_team2','match_id'];

	protected $with = ['match'];

	public function match(){
		return $this->belongsTo('App\Match');
	}
}

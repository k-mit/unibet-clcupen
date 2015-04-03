<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model {
	protected $fillable = ['user_id', 'bet_team1','bet_team2','match_id','score'];
	//
	public function user(){
		return $this->belongsTo('App\User');
	}
	public function match(){
		return $this->belongsTo('App\Match');
	}
	//kollar om detta funkar
}

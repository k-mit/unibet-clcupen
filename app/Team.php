<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

	protected $fillable = ['team_name', 'team_country','logo_path','country_flag_path','description'];

	public function results(){
		return $this->hasMany('App\Match','team1_id','id')+$this->hasMany('App\Match','team2_id','id');
	}
}

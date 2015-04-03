<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Highscore extends Model {

	protected $fillable = ['user_id', 'score','round'];


	public function user(){
		return $this->belongsTo('App\User');
	}

}

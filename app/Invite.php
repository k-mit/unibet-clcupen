<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model {

	protected $fillable = ['user_id', 'round_id'];

	public function user() {
		return $this->belongsTo('App\User');
	}	//

}

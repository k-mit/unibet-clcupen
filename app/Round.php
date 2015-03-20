<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model {

	//

	protected $dates=['start_date','end_date'];

	public function matches(){
		return $this->hasMany('App\Match');
	}
}

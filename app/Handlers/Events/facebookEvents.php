<?php namespace App\Handlers\Events;

use App\Events\reLogIn;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class facebookEvents {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  reLogIn  $event
	 * @return void
	 */
	public function handle(reLogIn $event)
	{

	}

}

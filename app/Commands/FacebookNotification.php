<?php namespace App\Commands;

use App\Commands\Command;

use App\Notification;
use Facebook\FacebookRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class FacebookNotification extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;

	protected $user, $notification, $facebook, $token;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(\App\User $user, \App\Notification $notification) {
		$this->user = $user;
		$this->notification = $notification;

		//$this->token = $this->facebook->getJavaScriptHelper()->getAccessToken();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle() {
		$facebook = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
		$fb_user_id = $this->user->facebook_user_id;
		$response = $facebook->post('/' . $fb_user_id . '/notifications', array(
			'href'     => '',
			'template' => $this->notification->template,
		), config('laravel-facebook-sdk.facebook_config.app_id').'|'.config('laravel-facebook-sdk.facebook_config.app_secret'));

	}

}

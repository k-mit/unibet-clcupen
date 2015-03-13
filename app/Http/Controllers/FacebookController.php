<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Facebook\Facebook;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;
use Facebook\GraphNodes\GraphObjectFactory;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Auth;


class FacebookController extends Controller {
	use SyncableGraphNodeTrait;
	public function getUserInfo(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
		try {
			$token = Session::get('fb_user_access_token');
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			return redirect('/facebook/login');
		}
		if (is_null($token)){
			$login_url = $fb->getLoginUrl(['email']);
			return redirect($login_url);
		}
		try {
			$response = $fb->get('/me?fields=id,name,email',$token);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			return redirect('/facebook/login');
		}

		// Convert the response to a `Facebook/GraphNodes/GraphUser` collection
		$facebook_user = $response->getGraphUser();

		// Create the user if it does not exist or update the existing entry.
		// This will only work if you've added the SyncableGraphNodeTrait to your User model.
		$user = User::createOrUpdateGraphNode($facebook_user);

		// Log the user into Laravel
		Auth::login($user);

		return $facebook_user ;
	}

	public function getFacebookFriends(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
		try {
			$token = Session::get('fb_user_access_token');
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			return redirect('/facebook/login');
		}
		if (is_null($token)){
			$login_url = $fb->getLoginUrl(['email']);
			return redirect($login_url);
		}
		try {
			$response = $fb->get('/me?fields=friends',$token);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			return redirect('/facebook/login');
		}

		return $response->getBody();

	}
	public function inviteFacebookFriend(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){

	}
}

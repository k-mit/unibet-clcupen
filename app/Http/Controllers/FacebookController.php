<?php namespace App\Http\Controllers;

use App\Events\reLogIn;
use App\Http\Requests;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk as LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Auth;

class FacebookController extends Controller {

	use SyncableGraphNodeTrait;
	/**
	 * @var $fb instance of LaravelFacebookSdk
	 */
	public $fb;
	public $token;

	public function __construct() {
		$this->fb = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');;
		//$this->getFacebookToken();
		return $this;
	}


	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return a facebook user as array
	 */
	public function getUserInfo() {

		try {
			$response = $this->fb->get('/me?fields=id,name,email', $this->token);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			redirect('/facebook/login');
		}

		// Convert the response to a `Facebook/GraphNodes/GraphUser` collection
		$facebook_user = $response->getGraphUser();

		// Create the user if it does not exist or update the existing entry.
		// This will only work if you've added the SyncableGraphNodeTrait to your User model.
		$user = User::createOrUpdateGraphNode($facebook_user);

		// Log the user into Laravel
		Auth::login($user);

		return $facebook_user;
	}

	/**
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package Facebook
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function getFacebookFriends() {
		try {
			$response = $this->fb->get('/me?fields=friends', $this->token);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			return redirect('/facebook/login');
		}
		$returnValue=$response->decodeBody();
		if (is_null($returnValue)){$returnValue=array();}
		return $returnValue;

	}

	public function inviteFacebookFriend() {

	}

	/**
	 * All the Variables that is needed for canvas.index
	 *
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @return a json object of all the vars needed to render first page.
	 */
	public function getAllVars($token) {
		$this->token = $token;
		\Event::fire(new reLogIn());

		$facebook_user = 	$this->getUserInfo()->asArray();

		$active_matches = 	$this->getActiveMatches();
		$user_bets = 		$this->getUserBets($facebook_user['id']);
		$fbFriends = 		$this->getFacebookFriends();
		$results = 			$this->results();
		$highscoreAll = 	$this->highScoreAll();
		$highscoreFriends =	$this->highscoreFriends($fbFriends);
		$statistics = 		$this->statistics();

		return [
			'facebook_user'    => $facebook_user,
			'active_matches'   => $active_matches,
			'user_bets'        => $user_bets,
			'fbFriends'        => $fbFriends,
			'results'          => $results,
			'highscoreAll'     => $highscoreAll,
			'highscoreFriends' => $highscoreFriends,
			'statistics'       => $statistics
		];
	}

	/**
	 * Get the matches that are in the active round
	 *
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 */
	public function getActiveMatches() {

		return array();
	}

	/**
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @param $user_id
	 * @return An array of the users bets
	 */
	public function getUserBets($user_id) {

	}

	/**
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @return array of match results
	 */
	public function results() {


	}

	public function highScoreAll() {

	}

	public function highscoreFriends($fbFriends) {

	}

	public function statistics() {

	}

	/**
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @return the session fb_user_access_token or null if it cant be found
	 */
	public function getFacebookToken() {
			$token = $this->fb->getCanvasHelper()->getAccessToken();

		if (!$token) {
			$token = Session::getToken();
		}
		if (!$token) {
				$token = $this->fb->getJavaScriptHelper()->getAccessToken();
		}
		if (!$token) {
				$token = Session::get('fb_user_access_token');
		}


		$this->token=$token;
	}
}

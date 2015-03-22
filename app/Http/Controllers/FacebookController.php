<?php namespace App\Http\Controllers;

use App\Bet;
use App\Events\reLogIn;
use App\Http\Requests;
use Illuminate\Http\Request;
use Facebook\Exceptions;

use App\Match;
use App\Round;
use Carbon\Carbon;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk as LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class FacebookController
 *
 * @package App\Http\Controllers
 */
class FacebookController extends Controller {

	use SyncableGraphNodeTrait;
	/**
	 * @var $fb instance of LaravelFacebookSdk
	 */
	public $fb;
	/**
	 * @var
	 */
	public $token;

	/**
	 *
	 */
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
	 * @return array of friends
	 */
	public function getFacebookFriends() {
		try {
			$response = $this->fb->get('/me?fields=friends', $this->token);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			dd(e);
		}
		$grespons = $response->getGraphObject();
		if (!isset($grespons['friends']) || is_null($grespons['friends'])) {
			$grespons['friends'] = array();
		}
		return $grespons['friends'];

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
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

		$facebook_user = $this->getUserInfo()->asArray();

		$active_round = $this->getActiveRound();
		$user_bets = $this->getUserBets($facebook_user['id']);
		$fbFriends = $this->getFacebookFriends();
		//$results = 			$this->results();
		$highscoreAll = $this->highScoreAll();
		$highscoreFriends = $this->highscoreFriends($fbFriends);
		$statistics = $this->statistics();

		return [
			'facebook_user'    => $facebook_user,
			'active_round'     => $active_round,
			'user_bets'        => $user_bets,
			'fbFriends'        => $fbFriends,
//			'results'          => $results,
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
	public function getActiveRound() {
		$round = Round::where('start_date', '<=', Carbon::now())->
		where('end_date', '>=', Carbon::now())->
		with(['matches', 'matches.team1', 'matches.team2', 'matches.result'])->
		get();
		return $round;
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

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
	public function highScoreAll() {

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $fbFriends
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
	public function highscoreFriends($fbFriends) {

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
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


		$this->token = $token;
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @todo   to save the combined scores to highscore table
	 * <code>
	 *
	 * </code>
	 */
	public function calculateRound() {
		DB::table('highscore')->delete();
		$bets = Bet::whereHas('match.round', function ($q) {
			$q->where('rounds.id', '=', 1);
		})->with(['match.result', 'user'])->get();
		foreach ($bets as $bet) {
			if ($this->get1x2($bet->match->result->goals_team1, $bet->match->result->goals_team2) == $this->get1x2($bet->bet_team1, $bet->bet_team2)) {
				$bet->score = 1;
			}
			if ($bet->match->result->goals_team1 == $bet->bet_team1 && $bet->match->result->goals_team2 == $bet->bet_team2) {
				$bet->score = 5;
			}
			$bet->save();

		}
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $result1
	 * @param $result2
	 * @return string
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
	public function get1x2($result1, $result2) {
		if ($result1 == $result2) return 'x';
		if ($result1 > $result2) return '1';
		if ($result1 < $result2) return '2';
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return string
	 * @todo   to fix for up to 4 matches to be posted
	 * <code>
	 *
	 * </code>
	 */
	public function saveBet(Request $request) {
		$count=0;
		for ($bet_nr=1;$bet_nr<5;$bet_nr++) {
			$oldbets = Bet::whereHas('match.round', function ($q) {
				$q->where('rounds.id', '=', $this->getActiveRound()[0]->id);
			})->where('user_id', '=', $request->user()->id)->where('match_id', '=', $request->input('match_id_'.$bet_nr))->get();
			if ($oldbets->count() == 0) {
				$bet = new Bet();
				$bet->user_id = $request->user()->id;
				$bet->bet_team1 = $request->input('bet_team1_'.$bet_nr);
				$bet->bet_team2 = $request->input('bet_team2_'.$bet_nr);
				$bet->match_id = $request->input('match_id_'.$bet_nr);
				$bet->save();
				$count++;
			}
		}
		if ($count>0) return '{"status":ok,"count_saved":'.$count.'}';
		return '{"status":fail,"count_saved":0}';

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return string
	 * @todo   ToDo
	 * <code>
	 *
	 * </code>
	 */
	public function saveInvite(Request $request) {
		$oldinvites = Invites::where('user_id','=',$request->user()->id)->get();
		if ($oldinvites->count() <= $this->getActiveRound()[0]->id*5) {
			$invite = new Invite();
			$invite->user_id = $request->user()->id;
			$invite->round_id = $this->getActiveRound()[0]->id;
			$invite->save();
			return 'save=ok';
		}
		return 'save=error';
	}

}


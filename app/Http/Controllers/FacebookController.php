<?php namespace App\Http\Controllers;

use App\Bet;
use App\Events\reLogIn;
use App\Highscore;
use App\Http\Requests;
use App\Invite;
use App\Snippet;
use Illuminate\Http\Request;
use Facebook\Exceptions;
use Illuminate\Support\Facades\DB;

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
		foreach ($grespons['friends'] as $friend_key => $friend) {
			$grespons['friends'][$friend_key]['user_id'] = User::where('facebook_user_id', '=', $friend['id'])->first()['id'];

		}

		return $grespons['friends'];

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
		//\Event::fire(new reLogIn());

		$facebook_user = $this->getUserInfo()->asArray();
		$active_round = $this->getActiveRound();
		$fbFriends = $this->getFacebookFriends();
		$snippets = $this->getSnippets();
		$highscoreAll = $this->highScoreAll();
		$highscoreFriends = $this->highscoreFriends($fbFriends);
		$oldbetsforthisround = Bet::whereHas('match.round', function ($q) {
			$q->where('rounds.id', '=', $this->getActiveRound()[0]->id);
		})->where('user_id', '=', $facebook_user['id'])->get();
		$havePlacedBet = ($oldbetsforthisround->count() > 1 ? 1 : 0);

		return [
			'facebook_user'    => $facebook_user,
			'active_round'     => $active_round,
			'fbFriends'        => $fbFriends,
			'havePlacedBet'    => $havePlacedBet,
			'highscoreAll'     => $highscoreAll,
			'highscoreFriends' => $highscoreFriends,
			'snippets'         => $snippets
		];
	}

	public function getSnippets() {
		$all_db_snippets = Snippet::get();
		return $all_db_snippets->keyBy('snippet_name');
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
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @todo   send users own score last.. and placements for all of them.
	 * <code>
	 *
	 * </code>
	 */
	public function highScoreAll() {
		return Highscore::where('round', '=', 10)->with('user')->orderBy('score', 'desc')->get();
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $fbFriends
	 * @todo   send users own score last.. and placements for all of them
	 * <code>
	 *
	 * </code>
	 */
	public function highscoreFriends($fbFriends) {
		$friendstring = '';
		foreach ($fbFriends as $friend) {
			$friends[] = $friend['user_id'];
		}
		$friends[] = Auth::user()->id;
		return Highscore::with('user')->where('round', '=', 10)->whereIn('user_id', $friends)->orderBy('score', 'desc')->get();

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
		$highscore_users = array();
		$highscore_users_per_round = array();
		DB::table('highscores')->delete();
		$bets = Bet::with(['match.result', 'user', 'match.round'])->get();
		/*		$bets = Bet::whereHas('match.round', function ($q) {
					$q->where('rounds.id', '=', 1);
				})->with(['match.result', 'user'])->get();*/
		foreach ($bets as $bet) {
			echo $bet->match->result->goals_team1 . '<br>';
			echo $bet->match->result->goals_team2 . '<br>';
			if ($this->get1x2($bet->match->result->goals_team1, $bet->match->result->goals_team2) == $this->get1x2($bet->bet_team1, $bet->bet_team2)) {
				$bet->score = 1;
			}
			if ($bet->match->result->goals_team1 == $bet->bet_team1 && $bet->match->result->goals_team2 == $bet->bet_team2) {
				$bet->score = 5;
			}
			if (!isset($highscore_users[$bet->user_id])) $highscore_users[$bet->user_id] = 0;
			if (!isset($highscore_users_per_round[$bet->match->round->id])) $highscore_users_per_round[$bet->match->round->id] = array();
			if (!isset($highscore_users_per_round[$bet->match->round->id][$bet->user_id])) $highscore_users_per_round[$bet->match->round->id][$bet->user_id] = 0;
			if (!isset($highscore_users_per_round[10][$bet->user_id])) $highscore_users_per_round[10][$bet->user_id] = 0;
			$highscore_users[$bet->user_id] += $bet->score;
			$highscore_users_per_round[$bet->match->round->id][$bet->user_id] += $bet->score;
			$highscore_users_per_round[10][$bet->user_id] += $bet->score;

			$bet->save();
		}
		$invites = Invite::select('user_id', 'round_id', DB::raw('count(*) as total_invites'))->groupBy('user_id')->get();
		foreach ($invites as $invite) {
			if ($invite->total_invites > 5) $invite->total_invites = 5;
			if (!isset($highscore_users[$invite->user_id])) $highscore_users[$invite->user_id] = 0;
			if (!isset($highscore_users_per_round[10])) $highscore_users_per_round[10] = 0;
			$highscore_users[$invite->user_id] += $invite->total_invites;
			$highscore_users_per_round[10][$invite->user_id] += $invite->total_invites;
		}
		foreach ($highscore_users_per_round as $round => $user_array) {
			foreach ($user_array as $user_id => $user_score) {
				$highscore_row = new Highscore();
				$highscore_row->user_id = $user_id;
				$highscore_row->score = $user_score;
				$highscore_row->round = $round;
				$highscore_row->save();
			}

		}

		dd($highscore_users_per_round);


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
	 */
	public function saveBet(Request $request) {
		$count = 0;
		for ($bet_nr = 1; $bet_nr < 5; $bet_nr++) {
			$oldbets = Bet::whereHas('match.round', function ($q) {
				$q->where('rounds.id', '=', $this->getActiveRound()[0]->id);
			})->where('user_id', '=', $request->user()->id)->where('match_id', '=', $request->input('match_id_' . $bet_nr))->get();
			if ($oldbets->count() == 0) {
				$bet = new Bet();
				$bet->user_id = $request->user()->id;
				$bet->bet_team1 = $request->input('bet_team1_' . $bet_nr);
				$bet->bet_team2 = $request->input('bet_team2_' . $bet_nr);
				$bet->match_id = $request->input('match_id_' . $bet_nr);
				$bet->save();
				$count++;
			}
		}
		if ($request->input('tiebreaker')) {
			$user = User::where('id', '=', $request->user()->id)->first();
			$user->tiebreaker = $request->input('tiebreaker');
			$user->save();
		}
		if ($count > 0) return '{"status":ok,"count_saved":' . $count . '}';
		return '{"status":fail,"count_saved":0}';

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return string
	 * @todo   Have to test this.. wrote it blind
	 */
	public function saveInvite(Request $request) {
		$oldinvites = Invites::where('user_id', '=', $request->user()->id)->get();
		if ($oldinvites->count() <= $this->getActiveRound()[0]->id * 5) {
			$invite = new Invite();
			$invite->user_id = $request->user()->id;
			$invite->round_id = $this->getActiveRound()[0]->id;
			$invite->save();
			return 'save=ok';
		}
		return 'save=error';
	}

}


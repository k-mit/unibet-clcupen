<?php namespace App\Http\Controllers;

use App\Bet;
use App\Commands\FacebookNotification;
use App\Helpers\ViewHelper;
use App\Highscore;
use App\Http\Requests;
use App\Invite;
use App\Notification;
use App\Snippet;
use Illuminate\Http\Request;
use Facebook\Exceptions;
use Illuminate\Support\Facades\DB;

use App\Match;
use App\Round;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk as LaravelFacebookSdk;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateBetRequest;
use Facebook\GraphNodes\GraphLocation;

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
		$this->fb = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
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

		$facebook_user = \Session::get('facebook_user');
		$user = \Auth::getUser();
		$facebook_user['user_id'] = $user['id'];
		$facebook_user['tiebreaker_1'] = $user['tiebreaker_1'];
		$facebook_user['tiebreaker_2'] = $user['tiebreaker_2'];
		$facebook_user['tiebreaker_3'] = $user['tiebreaker_3'];
		$facebook_user['tiebreaker_4'] = $user['tiebreaker_4'];
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
		} catch (FacebookSDKException $e) {
			return array();
		}
		$grespons = $response->getGraphObject();
		if (!isset($grespons['friends']) || is_null($grespons['friends'])) {
			$grespons['friends'] = array();
		}
		$friends = array();
		foreach ($grespons['friends'] as $friend_key => $friend) {
			$id = User::where('facebook_user_id', '=', $friend['id'])->first()['id'];
			if ($id) {
				$grespons['friends'][$friend_key]['user_id'] = $id;
				$friends[] = $grespons['friends'][$friend_key];
			}

		}

		return $friends;

	}

	/**
	 * All the Variables that is needed for canvas.index
	 *
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @return a json object of all the vars needed to render first page.
	 */
	public function getAllVars() {

		//\Event::fire(new reLogIn());

		$facebook_user = $this->getUserInfo()->asArray();
		$active_round = $this->getActiveRound();
		$fbFriends = $this->getFacebookFriends();

		$highscoreAll = $this->highScoreAll();
		$highscoreFriends = $this->highscoreFriends($fbFriends);
		$oldbetsforthisround = Bet::whereHas('match', function ($q) {
			$q->where('matches.round_id', '=', $this->getActiveRound()[0]->id);
		})->where('user_id', '=', $facebook_user['user_id'])->get();
		$havePlacedBet = ($oldbetsforthisround->count() > 1 ? 1 : 0);
		$tiebreaker_done = ($facebook_user['tiebreaker_' . $this->getActiveRound()[0]->id] == 0 ? 0 : 1);
		return [
			'facebook_user'    => $facebook_user,
			'active_round'     => $active_round,
			'fbFriends'        => $fbFriends,
			'havePlacedBet'    => $havePlacedBet,
			'highscoreAll'     => $highscoreAll,
			'highscoreFriends' => $highscoreFriends,
			'page'             => new ViewHelper(),
			'tiebreaker_done'  => $tiebreaker_done,
			'bets'             => $oldbetsforthisround
		];
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return mixed
	 */
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
		return Round::where('start_date', '<=', Carbon::now())->
		where('end_date', '>=', Carbon::now())->
		with(['matches', 'matches.team1', 'matches.team2', 'matches.result'])->
		get();

	}


	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 */
	public function highScoreAll() {
		$highscoreList = DB::select(DB::raw('select users.facebook_user_id as id, users.extra_score+highscores.score as total_score,highscores.*, users.name AS user_name FROM users,`highscores` where highscores.user_id = users.id AND `round` = 10 order by `total_score` desc'));
		$auth_id = Auth::user()->id;
		foreach ($highscoreList as $key => $highscoreUser) {
			$highscoreList[$key]->num = $key + 1;
			if ($highscoreUser->user_id == $auth_id) {
				$highscoreList['me'] = $highscoreUser;
				$highscoreList['me']->num = $highscoreList[$key]->num;
			}
		}
		if (!isset($highscoreFriends['me'])) {
			$highscoreFriends['me'] = Highscore::firstOrCreate(['user_id' => $auth_id, 'round' => 10]);
			$user = User::where('id', '=', $auth_id)->first();
			$highscoreFriends['me']->total_score = $user->extra_score;
			$highscoreFriends['me']->user_name = $user->name;
			$highscoreFriends['me']->num = count($highscoreFriends);
		}

		return $highscoreList;
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 *
	 * @param $fbFriends
	 */
	public function highscoreFriends($fbFriends) {
		foreach ($fbFriends as $friend) {
			$friends[] = $friend['user_id'];
		}
		$auth_id = Auth::user()->id;
		$friends[] = $auth_id;
		$friendsstring = implode(',', $friends);
		$friendsstring = str_replace(',,', ',', $friendsstring);
		$highscoreFriends = DB::select(DB::raw('select users.facebook_user_id as id, users.extra_score+highscores.score as total_score, highscores.*, users.name AS user_name FROM users,`highscores` where highscores.user_id = users.id AND `round` = 10 and `user_id` in (' . $friendsstring . ') order by `total_score` desc'));
		foreach ($highscoreFriends as $key => $highscoreFriend) {
			$highscoreFriends[$key]->num = $key + 1;
			if ($highscoreFriend->user_id == $auth_id) {
				$highscoreFriends['me'] = $highscoreFriend;
				$highscoreFriends['me']->num = $highscoreFriends[$key]->num;

			}
		}
		if (!isset($highscoreFriends['me'])) {
			$highscoreFriends['me'] = Highscore::firstOrCreate(['user_id' => $auth_id, 'round' => 10]);
			$user = User::where('id', '=', $auth_id)->first();
			$highscoreFriends['me']->total_score = $user->extra_score;
			$highscoreFriends['me']->user_name = $user->name;
			$highscoreFriends['me']->num = count($highscoreFriends);
		}
		return $highscoreFriends;

	}


	/**
	 * @author  Pontus Kindblad & Anton Kindblad
	 * @access  public
	 * @package facebook
	 * @return the session facebook_access_token or null if it cant be found
	 */
	public function getFacebookToken() {
		$token = Session::get('facebook_access_token');
		$this->token = $token;
		return $token;
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 */
	public function calculateRound() {
		$highscore_users = array();
		$highscore_users_per_round = array();
		DB::table('highscores')->delete();
		$bets = Bet::with(['match.result', 'user', 'match.round'])->get();
		foreach ($bets as $bet) {
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
			$user_obj = User::where('id', '=', $invite->user_id)->first();
			$user_obj->extra_score = $invite->total_invites;
			$user_obj->save();
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
	 *
	 * @param $result1
	 * @param $result2
	 *
	 * @return string
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
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function saveBet(CreateBetRequest $request) {
		$count = 0;
		for ($bet_nr = 1; $bet_nr < 5; $bet_nr++) {
			$oldbets = Bet::whereHas('match.round', function ($q) {
				$q->where('rounds.id', '=', $this->getActiveRound()[0]->id);
			})->where('user_id', '=', $request->user()->id)->where('match_id', '=', $request->input('match_id_' . $bet_nr))->get();
			if ($oldbets->count() == 0) {
				if ($request->has('bet_team1_' . $bet_nr) && $request->has('bet_team2_' . $bet_nr) && $request->has('match_id_' . $bet_nr)) {
					$bet = new Bet();
					$bet->user_id = $request->user()->id;
					$bet->bet_team1 = $request->input('bet_team1_' . $bet_nr);
					$bet->bet_team2 = $request->input('bet_team2_' . $bet_nr);
					$bet->match_id = $request->input('match_id_' . $bet_nr);
					$bet->save();
					$count++;
				}
			}
		}
		$user = User::where('id', '=', $request->user()->id)->first();
		if ($request->has('tiebreaker_1')) {
			$user->tiebreaker_1 = $request->input('tiebreaker_1');
		}
		if ($request->has('tiebreaker_2')) {
			$user->tiebreaker_2 = $request->input('tiebreaker_2');
		}
		if ($request->has('tiebreaker_3')) {
			$user->tiebreaker_3 = $request->input('tiebreaker_3');
		}
		if ($request->has('tiebreaker_4')) {
			$user->tiebreaker_4 = $request->input('tiebreaker_4');
		}
		if ($request->has('shirt_size')) {
			$user->shirt_size = $request->input('shirt_size');
		}
		if ($request->has('email2')) {
			$user->email2 = $request->input('email2');
		}
		if ($request->has('address')) {
			$user->address = $request->input('address');
		}
		$user->save();
		if ($count > 0) return Redirect::back();
//		if ($count > 0) return '{"status":ok,"count_saved":' . $count . '}';
		return Redirect::back();
		return '{"status":fail,"count_saved":0}';

	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return string
	 */
	public function saveInvite(Request $request) {
		$oldinvites = Invite::where('user_id', '=', $request->user()->id)->get();
		if ($request->has('facebook_friend_id')) {
			foreach ($request->input('facebook_friend_id', []) as $facebook_friend_id) {
			$oldinvites = Invite::where('user_id', '=', $request->user()->id)->get();
				if ($oldinvites->count() < 5) {
/*					$user = User::where('id', '=', $request->user()->id)->first();
					$user->extra_score++;
					$user->save();*/
					$invite = new Invite();
					$invite->user_id = $request->user()->id;
					$invite->round_id = $this->getActiveRound()[0]->id;
					$invite->facebook_friend_id = $facebook_friend_id;
					$invite->save();
					return 'save=ok';
				}
			}
		}
		return 'save=error';
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return \Illuminate\View\View
	 */
	public function viewCanvas() {
		$this->fb->setDefaultAccessToken($this->getFacebookToken());
		return view('canvas.index', $this->getAllVars());
	}

	/**
	 * Returns the view that is used for login
	 *
	 * @return \Illuminate\View\View
	 */
	public function viewLogin() {
		return view('canvas.login',array('page' => new ViewHelper()));
	}
}

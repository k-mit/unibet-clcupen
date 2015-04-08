<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Commands\FacebookNotification;

use App\Notification;
use App\Snippet;
use Illuminate\Http\Request;
use App\User;
use App\Highscore;
use Session;
use Excel;
use Illuminate\Support\Facades\DB;
use Auth;

/**
 * Class AdminController
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller {

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function boot(Request $request) {
		if (\Auth::user()->id > 4) {

			return redirect('/facebook/canvas');
		}
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return string
	 */
	public function logout() {
		\Auth::logout();
		Session::forget('facebook_access_token');
		Session::forget('facebook_user');

		//TODO clear the facebook cookie so we don't pull it when coming back, the following lines do not work
		$fb_key = 'fbsr_' . config('laravel-facebook-sdk.facebook_config.app_id');
		setcookie($fb_key, '', time() - 3600);

		return 'logged out';
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return \Illuminate\View\View
	 */
	public function snippets() {
		return view('admin/snippets', ['snippets_list' => Snippet::get()]);
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return \Illuminate\View\View
	 */
	public function notifications() {
		return view('admin/notifications', ['notifications_list' => Notification::get()]);
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return \Illuminate\View\View
	 */
	public function notificationsPersons() {
		$hiscoreUsers_raw = Highscore::with('user')->where('round', '=', 10)->orderBy('score', 'desc')->get();
		foreach ($hiscoreUsers_raw as $hiscoreUser) {
			$hiscoreUsers[$hiscoreUser->user_id] = $hiscoreUser->user->name . ' ' . $hiscoreUser->user->facebook_user_id;
		}
		return view('admin/people_notification', ['notifications_list' => Notification::get(), 'users' => $hiscoreUsers]);
	}


	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $round
	 * @return \Illuminate\View\View
	 */
	public function roundResults($round) {
		return view('admin.roundresults', ['highscore'=>$this->highScoreRoundPage($round),'round'=>$round]);
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function saveNotification(Request $request) {
		Notification::create($request->all());
		\Session::flash('flash_message', 'Notification saved.');

		return redirect('admin/notifications');
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function saveSnippet(Request $request) {
		$allrequests = $request->all();
		$allrequests = array_except($allrequests, ['_token', 'id']);
		Snippet::where('id', '=', $request['id'])->update($allrequests);
		\Session::flash('flash_message', 'Snippet "' . $request['snippet_name'] . '" saved.');
		return redirect('admin/snippets');
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function notifyAll(Request $request) {
		$all_users = User::get();
		$notification = Notification::where('id', '=', $request->input('notification_id'))->first();
		foreach ($all_users as $user) {
			//echo 'Sending to: ' . $user->name . '(' . $user->facebook_user_id . ')' . '<br>';
			$this->dispatch(
				new FacebookNotification($user, $notification)
			);
		}
		return redirect('admin/notifications');
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function notifyPersons(Request $request) {
		$users_selected = array_values($request->get('users'));
		$all_users = User::wherein('id', $users_selected)->get();
		$notification = Notification::where('id', '=', $request->input('notification_id'))->first();
		foreach ($all_users as $user) {
			$this->dispatch(
				new FacebookNotification($user, $notification)
			);
		}

		return redirect('admin/notificationsPersons');
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $round_id
	 * @return mixed
	 */
	public function highScoreRoundPage($round_id) {
		$highscoreList = DB::select(DB::raw('select
												users.extra_score+highscores.score as total_score,
												ABS(cast(tiebreaker_results.result as signed)-users.tiebreaker_'.$round_id.') AS tiebreaker_diff,
												highscores.*,
												users.*
												FROM
													users,
													highscores,
													tiebreaker_results
												WHERE
													tiebreaker_results.round_id =highscores.round AND
													highscores.user_id = users.id AND
													`round` = ' . $round_id . '
												ORDER BY
													`total_score` desc,
													tiebreaker_diff asc'));
		foreach ($highscoreList as $key => $highscoreUser) {
			$highscoreList[$key]->num = $key + 1;
		}
		return $highscoreList;
	}
	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @return model with highscores in
	 */
	public function highScoreRound($round_id) {
		$highscoreList = Highscore::with('user')->where('round', '=', $round_id)->orderBy('score', 'desc')->get();

		foreach ($highscoreList as $key => $highscoreUser) {
			$highscoreList[$key]->num = $key + 1;
		}
		return $highscoreList;
	}

	/**
	 * @author Pontus Kindblad & Anton Kindblad
	 * @access public
	 * @package
	 * @param $round
	 */
	public function excelExport($round) {
		$model = $this->highScoreRound($round)->toArray();
		foreach ($model as $key => $value) {
			foreach($value['user'] as $user_key => $user_value){
				$model[$key][$user_key]=$user_value;
			}
			if ($round<9) {
 				$model[$key]['score']= $model[$key]['score'] + $model[$key]['extra_score'];
				$model[$key]['total_score'] = $model[$key]['score'] + $model[$key]['extra_score'];
			}
		}
		Excel::create('unibet_round', function ($excel) use ($model) {

			$excel->sheet('Round', function ($sheet) use ($model) {
				$sheet->setColumnFormat(array(
											'I' => '0'
										));
				$sheet->fromArray($model, null, 'A1', true);

			});

		})->export('xls');
	}

}

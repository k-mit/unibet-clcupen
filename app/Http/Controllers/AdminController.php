<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Commands\FacebookNotification;

use App\Notification;
use App\Snippet;
use Illuminate\Http\Request;
use App\User;
use App\Highscore;
class AdminController extends Controller {


	public function snippets(){
		return view('admin/snippets',['snippets_list'=>Snippet::get()]);
	}

	public function notifications() {
		return view('admin/notifications',['notifications_list'=>Notification::get()]);
	}

	public function notificationsPersons() {
		$hiscoreUsers_raw = Highscore::with('user')->where('round', '=', 10)->orderBy('score', 'desc')->get();
		foreach ($hiscoreUsers_raw as $hiscoreUser){
			$hiscoreUsers[$hiscoreUser->user_id]=$hiscoreUser->user->name.' '.$hiscoreUser->user->facebook_user_id;
		}
		return view('admin/people_notification',['notifications_list'=>Notification::get(),'users'=>$hiscoreUsers]);
	}

	public function saveNotification(Request $request){
		Notification::create($request->all());
		\Session::flash('flash_message','Notification saved.');

		return redirect('admin/notifications');
	}

	public function saveSnippet(Request $request){
		$allrequests = $request->all();
		$allrequests = array_except($allrequests, ['_token','id']);
		Snippet::where('id','=',$request['id'])->update($allrequests);
		\Session::flash('flash_message','Snippet "'.$request['snippet_name'].'" saved.');
		return redirect('admin/snippets');
	}
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
	public function notifyPersons(Request $request) {
		$users_selected = array_values($request->get('users'));
		$all_users = User::wherein('id',$users_selected)->get();
		$notification = Notification::where('id', '=', $request->input('notification_id'))->first();
		foreach ($all_users as $user) {
			$this->dispatch(
				new FacebookNotification($user, $notification)
			);
		}

		return redirect('admin/notificationsPersons');
	}
}

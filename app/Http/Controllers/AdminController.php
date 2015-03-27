<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Commands\FacebookNotification;

use App\Notification;
use App\Snippet;
use Illuminate\Http\Request;
use App\User;
class AdminController extends Controller {




	public function snippets(){
		return view('admin/snippets',['snippets_list'=>Snippet::get()]);
	}

	public function notifications() {
		return view('admin/notifications',['notifications_list'=>Notification::get()]);
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
		foreach ($all_users as $user) {
			//echo 'Sending to: ' . $user->name . '(' . $user->facebook_user_id . ')' . '<br>';
			$this->dispatch(
				new FacebookNotification(User::where('facebook_user_id', '=', $user->facebook_user_id)->first(), Notification::where('id', '=', $request->input('notification_id'))->first())
			);
		}
		return redirect('admin/notifications');
	}
	public function notifyPersons(Request $request) {
		$all_users = User::get()-wherein('id',$request->get('user_ids'));
		foreach ($all_users as $user) {
			$this->dispatch(
				new FacebookNotification(User::where('facebook_user_id', '=', $user->facebook_user_id)->first(), Notification::where('id', '=', $request->input('notification_id'))->first())
			);
		}
		return redirect('admin/notifications');
	}
}

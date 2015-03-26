<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Commands\FacebookNotification;

use App\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller {


	public function notifyAll($notification_id) {
		$all_users = User::get();
		foreach ($all_users as $user) {
			echo 'Sending to: ' . $user->name . '(' . $user->facebook_user_id . ')' . '<br>';
			$this->dispatch(
				new FacebookNotification(User::where('facebook_user_id', '=', $user->facebook_user_id)->first(), Notification::where('id', '=', $notification_id)->first())
			);
		}
	}

	public function notifications() {
		$notifications_list = Notification::get();


		return view('admin/notifications',['notifications_list'=>$notifications_list]);
	}

}

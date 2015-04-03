<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Match;
use App\Round;
use App\Team;
use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\Facades\DataSet;
use Zofe\Rapyd\DataForm\DataForm;
use Zofe\Rapyd\Facades\DataEdit;
use App\Result;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminMatchesController extends Controller {

	public function getGrid() {

		$grid = DataGrid::source(Match::with('team1', 'team2', 'round'));
		$grid->add('id', 'ID', true)->style("width:100px");
		$grid->add('team1.team_name', 'Team 1')->style("width:200px;text-align:center");
		$grid->add('team2.team_name', 'Team 2')->style('width:200px;text-align:center');
		$grid->add('match_time', 'Match time');
		$grid->add('round.round_name', 'Round');

		$grid->edit('/admin/matches/edit', 'Edit', 'modify');
		$grid->orderBy('id', 'asc');
		$grid->paginate(20);


		return View('admin.matchesgrid', compact('grid'));
	}

	public function anyEdit(Request $request) {
		$edit = DataForm::source(Match::find($request->input('modify')));
		$edit->label('Edit Match Teams');
		$edit->link("/admin/matches/grid", "Matches", "TR")->back();
		$edit->set('id', $edit->model->id);
		$edit->add('team1_id', 'Home team', 'radiogroup')->options(Team::lists('team_name', 'id'));
		$edit->add('team2_id', 'Away team', 'radiogroup')->options(Team::lists('team_name', 'id'));
		$edit->add('round_id', 'Round', 'radiogroup')->options(Round::lists('round_name', 'id'));
//		$edit->add('match_time', 'Time('.$edit->model->match_time.')' , 'datetime')->format('Y-m-d H:i');
		$edit->submit('Save');

		$edit->saved(function () use ($edit) {
			$edit->message("ok record saved");
			$edit->link("/admin/matches/grid","back to the list");
		});

		return $edit->view('admin.matchesedit', compact('edit'));

	}
}

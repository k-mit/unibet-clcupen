<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Match;
use App\Team;
use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\Facades\DataSet;
use Zofe\Rapyd\DataForm\DataForm;
use Zofe\Rapyd\Facades\DataEdit;
use App\Result;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class AdminResultsController extends Controller {

	public function getGrid() {

		$grid = DataGrid::source(Result::with('match','match.team1','match.team2'));
		$grid->add('id', 'ID', true)->style("width:100px");
		$grid->add('match.team1.team_name','Team 1')->style("width:200px;text-align:center");
		$grid->add('goals_team1', '')->style("width:30px");
		$grid->add('{{"-"}}', '')->style("width:10px");
		$grid->add('goals_team2', '')->style("width:s0px");
		$grid->add('match.team2.team_name','Team 2')->style('width:200px;text-align:center');
		$grid->add('match.match_time', 'Match time');

		$grid->edit('/admin/results/edit', 'Edit', 'modify');
		$grid->link('/admin/results/edit',"New match result", "TR");
		$grid->orderBy('id', 'asc');
		$grid->paginate(200);


		return View('admin.resultsgrid', compact('grid'));
	}

	public function anyEdit(Request $request) {
		$matches = Match::with('team1','team2')->get();
		foreach ($matches as $key => $match) {
			$matches[$key]->match_name = $match->team1->team_name.'–'.$match->team2->team_name.'<br>';
		}
		if ($request->input('modify')) {
			$edit = DataForm::source(Result::with('match', 'match.team1', 'match.team2')->find($request->input('modify')));
		}else{
			$edit = DataEdit::source(new Result());
		}
		$edit->label('Edit Match Results');
		$edit->link("/admin/results/grid", "Results", "TR")->back();
		$edit->add('match_id', 'Match','radiogroup')->options($matches->lists('match_name','id'));
		if ($request->input('modify')) {
			$edit->set('id', $edit->model->id);
			$edit->add('goals_team1', $edit->model->match->team1->team_name, 'text');
			$edit->add('goals_team2', $edit->model->match->team2->team_name, 'text');
			$edit->submit('Save');
		}else{
			$edit->add('goals_team1', 'Home team goals', 'text');
			$edit->add('goals_team2', 'Away team goals', 'text');
		}

		$edit->saved(function () use ($edit) {
			$edit->message("ok record saved");
			$edit->link("/admin/results/grid","back to the list");
		});

		return $edit->view('admin.resultsedit', compact('edit'));

	}
}

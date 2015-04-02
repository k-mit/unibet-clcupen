<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\Facades\DataSet;
use Zofe\Rapyd\DataForm\DataForm;
use Zofe\Rapyd\Facades\DataEdit;
use App\Result;
use Illuminate\View\View;
class AdminResultsController extends Controller {

	public function getGrid() {

		$grid = DataGrid::source(Result::with('match','match.team1','match.team2'));
		$grid->add('id', 'ID', true)->style("width:100px");
		$grid->add('goals_team1', 'Goals team 1');
		$grid->add('match.team1.team_name','Team 1 name');
		$grid->add('goals_team2', 'Goals team 2');
		$grid->add('match.team2.team_name','Team 2 name');
		$grid->add('match.match_time', 'Match time');

		$grid->edit('/admin/results/edit', 'Edit', 'show|modify');
		$grid->orderBy('id', 'asc');
		$grid->paginate(20);

		$grid->row(function ($row) {
			if ($row->cell('id')->value == 20) {
				$row->style("background-color:#CCFF66");
			} elseif ($row->cell('id')->value > 15) {
				$row->cell('title')->style("font-weight:bold");
				$row->style("color:#f00");
			}
		});

		return View('admin.resultsgrid', compact('grid'));
	}

	public function anyEdit(Request $request,$first) {
//		if ($request->Input('do_delete') == 1) return "not the first";
		$edit = DataForm::source(Result::with('match','match.team1','match.team2')->find($first));
		//$dataset = DataSet::source(Result::with('match','match.team1','match.team2'));
		$edit->label('Edit Results');
		$edit->link("/admin/results/grid", "Rounds", "TR")->back();
		$edit->add('match_id', 'Match id','text');
		$edit->add('goals_team1', 'Goals team 1','text');
		$edit->add('goals_team2', 'Goals team 2','text');
		$edit->add('match_id', 'Match id','text');

		return $edit->view('admin.resultsedit', compact('edit'));

	}
}

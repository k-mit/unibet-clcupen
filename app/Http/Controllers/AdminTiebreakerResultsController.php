<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Match;
use App\Team;
use App\TiebreakerResult;
use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\Facades\DataSet;
use Zofe\Rapyd\DataForm\DataForm;
use Zofe\Rapyd\Facades\DataEdit;
use App\Result;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
class AdminTiebreakerResultsController extends Controller {

	public function getGrid() {

		$grid = DataGrid::source('tiebreaker_results');
		$grid->add('id', 'ID', true)->style("width:50px");
		$grid->add('result','Result')->style("width:70px;text-align:center");
		$grid->add('round_id', 'Round')->style("width:50px");

		$grid->edit('/admin/tiebreaker/edit', 'Edit', 'modify');
		$grid->orderBy('id', 'asc');
		$grid->paginate(200);


		return View('admin.tiebreaker_results_grid', compact('grid'));
	}

	public function anyEdit(Request $request) {
		$edit = DataEdit::source(new TiebreakerResult());
		$edit->label('Edit Tiebreaker Results');
		$edit->link("/admin/tiebreaker/grid", "Tiebreaker Results", "TR")->back();
		$edit->add('result', 'Result', 'text');

		$edit->saved(function () use ($edit) {
			$edit->message("ok record saved");
			$edit->link("/admin/tiebreaker/grid","back to the list");
		});

		return $edit->view('admin.tiebreaker_results_edit', compact('edit'));

	}
}

<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Zofe\Rapyd\DataGrid\DataGrid;
use Zofe\Rapyd\Facades\DataEdit;
use App\Round;
use Illuminate\View\View;

class AdminRoundsController extends Controller {

	public function getGrid() {

		$grid = DataGrid::source('rounds');

		$grid->add('id', 'ID', true)->style("width:100px");
		$grid->add('round_name', 'Round name');
		$grid->add('start_date', 'Start Date');
		$grid->add('end_date', 'End Date');

		$grid->edit('/admin/rounds/edit', 'Edit', 'modify');
		//$grid->link('/admin/rounds/edit', "New Article", "TR");
		$grid->orderBy('start_date', 'asc');
		$grid->paginate(20);

		$grid->row(function ($row) {
			if ($row->cell('id')->value == 20) {
				$row->style("background-color:#CCFF66");
			} elseif ($row->cell('id')->value > 15) {
				$row->cell('title')->style("font-weight:bold");
				$row->style("color:#f00");
			}
		});

		return View('admin.roundsgrid', compact('grid'));
	}

	public function anyEdit(Request $request)
	{
		if ($request->Input('do_delete')==1) return  "not the first";

		$edit = DataEdit::source(new Round());
		$edit->label('Edit Rounds');
		$edit->link("/admin/rounds/grid","Rounds", "TR")->back();
		$edit->add('round_name', 'Round name','text');
		$edit->add('start_date', 'Start Date','date')->format('Y-m-d', 'sv');
		$edit->add('end_date', 'End Date','date')->format('Y-m-d', 'sv');

		return $edit->view('admin.roundsedit', compact('edit'));

	}


}

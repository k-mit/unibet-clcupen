<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('results', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('goals_team1');
			$table->integer('goals_team2');
			$table->integer('match_id')->unsigned()->index();
			$table->timestamps();
		});
		DB::statement( 'alter table `results` add constraint results_match_id_foreign foreign key (`match_id`) references `matches` (`id`) on delete cascade' );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('results', function ($table) {
			$table->dropForeign('results_match_id_foreign');
		});
		Schema::drop('results');
	}

}

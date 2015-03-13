<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('matches', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('team1_id')->unsigned()->index();
			$table->foreign('team1_id')->references('id')->on('teams');
			$table->integer('team2_id')->unsigned()->index();
			$table->foreign('team2_id')->references('id')->on('teams');
			$table->dateTime('match_time');
			$table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('matches', function ($table) {
			$table->dropForeign('matches_team1_id_foreign');
		});
		Schema::table('matches', function ($table) {
			$table->dropForeign('matches_team2_id_foreign');
		});
		Schema::drop('matches');
	}

}

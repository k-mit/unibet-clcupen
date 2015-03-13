<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('bet_team1');
			$table->integer('bet_team2');
			$table->integer('match_id')->unsigned()->index();
			$table->integer('score')->default(0);
			$table->timestamps();

		});
		DB::statement( 'alter table `bets` add constraint bets_user_id_foreign foreign key (`user_id`) references `users` (`id`) on delete cascade' );
		DB::statement( 'alter table `bets` add constraint bets_match_id_foreign foreign key (`match_id`) references `matches` (`id`) on delete cascade' );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bets', function ($table) {
			$table->dropForeign('bets_user_id_foreign');
		});
		Schema::table('bets', function ($table) {
			$table->dropForeign('bets_match_id_foreign');
		});

		Schema::drop('bets');
	}

}

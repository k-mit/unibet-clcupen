<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('facebook_user_id')->unsigned()->index();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('access_token')->nullable();
			$table->integer('extra_score');
			$table->integer('tiebreaker');
			$table->string('shirt_size','20');
			$table->string('email2');
			$table->text('address');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}

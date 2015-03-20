<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		$this->call('UserTableSeeder');
		$this->call('RoundsTableSeeder');
		$this->call('TeamsTableSeeder');
		$this->call('MatchesTableSeeder');
	}

}
class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();
		DB::table('users')->insert(array('facebook_user_id'=>1382918398693972,'name'=>'K-mit Kindblad','email' => 'pontus+facebook@k-mit.se'));
		DB::table('users')->insert(array('facebook_user_id'=>1390591374592363,'name'=>'Susan Amibdedadgbe Qinstein','email' => 'zynsusw_qinstein_1426800568@tfbnw.net'));
		DB::table('users')->insert(array('facebook_user_id'=>1374518872874153,'name'=>'Dorothy Amidhaeijdif Greeneescu','email' => 'kofjsor_greeneescu_1426800569@tfbnw.net'));

	}

}
class RoundsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('rounds')->delete();
		DB::table('rounds')->insert(array('start_date'=>'2015-03-11 21:00','end_date'=>'2015-04-01 21:00','round_name' => 'pre-round'));
		DB::table('rounds')->insert(array('start_date'=>'2015-04-02 21:00','end_date'=>'2015-04-28 21:00','round_name' => 'round-one'));

	}

}
class MatchesTableSeeder extends Seeder {

	public function run()
	{
		DB::table('matches')->delete();
		DB::table('matches')->insert(array('team1_id'=>'1','team2_id'=>'2','match_time' => '2015-03-10 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'2','team2_id'=>'3','match_time' => '2015-03-11 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'3','team2_id'=>'4','match_time' => '2015-03-12 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'4','team2_id'=>'5','match_time' => '2015-03-13 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'5','team2_id'=>'1','match_time' => '2015-03-14 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'4','team2_id'=>'2','match_time' => '2015-03-15 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'3','team2_id'=>'2','match_time' => '2015-03-16 21:00','round_id'=>1));
		DB::table('matches')->insert(array('team1_id'=>'1','team2_id'=>'2','match_time' => '2015-03-10 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'2','team2_id'=>'3','match_time' => '2015-03-11 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'3','team2_id'=>'4','match_time' => '2015-03-12 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'4','team2_id'=>'5','match_time' => '2015-03-13 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'5','team2_id'=>'1','match_time' => '2015-03-14 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'4','team2_id'=>'2','match_time' => '2015-03-15 21:00','round_id'=>2));
		DB::table('matches')->insert(array('team1_id'=>'3','team2_id'=>'2','match_time' => '2015-03-16 21:00','round_id'=>2));

	}

}
class TeamsTableSeeder extends Seeder {

	public function run() {
		DB::table('teams')->delete();
		DB::table('teams')->insert(array('id'=>1,'team_name' => 'Club Atlético de Madrid', 'team_country' => 'Spain', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50124.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/ESP.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>2,'team_name' => 'FC Barcelona', 'team_country' => 'Spain', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50080.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/ESP.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>3,'team_name' => 'FC Bayern München', 'team_country' => 'Germany', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50037.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/GER.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>4,'team_name' => 'Juventus', 'team_country' => 'Italy', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50139.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/ITA.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>5,'team_name' => 'AS Monaco FC', 'team_country' => 'France', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50023.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/FRA.png', 'description' => ''));

	}
}

/*
			$table->string('team_name',100);
			$table->string('team_country',100);
			$table->text('logo_path');
			$table->text('country_flag_path');
			$table->text('description');

 */
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
		$this->call('ResultsTableSeeder');
		$this->call('BetsTableSeeder');
	}

}
class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();
		DB::table('users')->insert(array('id'=>1,'facebook_user_id'=>1382918398693972,'name'=>'K-mit Kindblad','email' => 'pontus+facebook@k-mit.se'));
		DB::table('users')->insert(array('id'=>2,'facebook_user_id'=>1390591374592363,'name'=>'Susan Amibdedadgbe Qinstein','email' => 'zynsusw_qinstein_1426800568@tfbnw.net'));
		DB::table('users')->insert(array('id'=>3,'facebook_user_id'=>1374518872874153,'name'=>'Dorothy Amidhaeijdif Greeneescu','email' => 'kofjsor_greeneescu_1426800569@tfbnw.net'));

	}

}
class RoundsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('rounds')->delete();
		DB::table('rounds')->insert(array('start_date'=>'2015-03-11 21:00','end_date'=>'2015-04-14 20:30','round_name' => 'pre-round'));
		DB::table('rounds')->insert(array('start_date'=>'2015-04-02 21:00','end_date'=>'2015-04-28 20:30','round_name' => 'round-one'));

	}

}
class MatchesTableSeeder extends Seeder {

	public function run()
	{
		DB::table('matches')->delete();
		DB::table('matches')->insert(array('id'=>1,'team1_id'=>'1','team2_id'=>'8','match_time' => '2015-04-14 20:45','round_id'=>1));
		DB::table('matches')->insert(array('id'=>2,'team1_id'=>'4','team2_id'=>'5','match_time' => '2015-04-14 20:45','round_id'=>1));
		DB::table('matches')->insert(array('id'=>3,'team1_id'=>'6','team2_id'=>'2','match_time' => '2015-04-15 20:45','round_id'=>1));
		DB::table('matches')->insert(array('id'=>4,'team1_id'=>'7','team2_id'=>'3','match_time' => '2015-04-15 20:45','round_id'=>1));

		DB::table('matches')->insert(array('id'=>5,'team1_id'=>'8','team2_id'=>'1','match_time' => '2015-04-28 20:45','round_id'=>2));
		DB::table('matches')->insert(array('id'=>6,'team1_id'=>'5','team2_id'=>'4','match_time' => '2015-04-28 20:45','round_id'=>2));
		DB::table('matches')->insert(array('id'=>7,'team1_id'=>'2','team2_id'=>'6','match_time' => '2015-04-29 20:45','round_id'=>2));
		DB::table('matches')->insert(array('id'=>8,'team1_id'=>'3','team2_id'=>'7','match_time' => '2015-04-29 20:45','round_id'=>2));

		DB::table('matches')->insert(array('id'=>9,'team1_id'=>'8','team2_id'=>'1','match_time' => '2015-05-12 20:45','round_id'=>3));
		DB::table('matches')->insert(array('id'=>10,'team1_id'=>'5','team2_id'=>'4','match_time' => '2015-05-12 20:45','round_id'=>3));

		DB::table('matches')->insert(array('id'=>11,'team1_id'=>'2','team2_id'=>'6','match_time' => '2015-05-24 20:45','round_id'=>4));
		DB::table('matches')->insert(array('id'=>12,'team1_id'=>'3','team2_id'=>'7','match_time' => '2015-05-24 20:45','round_id'=>4));

		DB::table('matches')->insert(array('id'=>13,'team1_id'=>'2','team2_id'=>'3','match_time' => '2015-06-06 20:45','round_id'=>5));

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
		DB::table('teams')->insert(array('id'=>6,'team_name' => 'Paris Saint-Germain', 'team_country' => 'France', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/52747.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/FRA.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>7,'team_name' => 'FC Porto', 'team_country' => 'Portugal', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50064.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/POR.png', 'description' => ''));
		DB::table('teams')->insert(array('id'=>8,'team_name' => 'Real Madrid CF', 'team_country' => 'Spain', 'logo_path' => 'http://img.uefa.com/imgml/TP/teams/logos/70x70/50051.png', 'country_flag_path' => 'http://img.uefa.com/imgml/flags/18x18/ESP.png', 'description' => ''));

	}
}

class ResultsTableSeeder extends Seeder {

	public function run() {
		DB::table('results')->delete();
		DB::table('results')->insert(array('id'=>1,'goals_team1' => '1', 'goals_team2' => '7', 'match_id' => '1'));
		DB::table('results')->insert(array('id'=>2,'goals_team1' => '2', 'goals_team2' => '6', 'match_id' => '2'));
		DB::table('results')->insert(array('id'=>3,'goals_team1' => '3', 'goals_team2' => '5', 'match_id' => '3'));
		DB::table('results')->insert(array('id'=>4,'goals_team1' => '4', 'goals_team2' => '4', 'match_id' => '4'));
		DB::table('results')->insert(array('id'=>5,'goals_team1' => '5', 'goals_team2' => '3', 'match_id' => '5'));
		DB::table('results')->insert(array('id'=>6,'goals_team1' => '6', 'goals_team2' => '2', 'match_id' => '6'));
		DB::table('results')->insert(array('id'=>7,'goals_team1' => '7', 'goals_team2' => '1', 'match_id' => '7'));

	}
}
class BetsTableSeeder extends Seeder {

	public function run() {
		DB::table('bets')->delete();
		DB::table('bets')->insert(array('id'=>1,'user_id' => '1', 'bet_team1' => '7', 'bet_team2' => '1','match_id'=>'1'));
		DB::table('bets')->insert(array('id'=>2,'user_id' => '2', 'bet_team1' => '1', 'bet_team2' => '7','match_id'=>'1'));
		DB::table('bets')->insert(array('id'=>3,'user_id' => '3', 'bet_team1' => '1', 'bet_team2' => '1','match_id'=>'1'));
		DB::table('bets')->insert(array('id'=>4,'user_id' => '1', 'bet_team1' => '2', 'bet_team2' => '1','match_id'=>'2'));
		DB::table('bets')->insert(array('id'=>5,'user_id' => '2', 'bet_team1' => '3', 'bet_team2' => '7','match_id'=>'2'));
		DB::table('bets')->insert(array('id'=>6,'user_id' => '3', 'bet_team1' => '1', 'bet_team2' => '1','match_id'=>'2'));
		DB::table('bets')->insert(array('id'=>7,'user_id' => '1', 'bet_team1' => '7', 'bet_team2' => '1','match_id'=>'3'));
		DB::table('bets')->insert(array('id'=>8,'user_id' => '2', 'bet_team1' => '1', 'bet_team2' => '7','match_id'=>'3'));
		for ($counter = 9;$counter<5000;$counter++) {
			DB::table('bets')->insert(array('id' => $counter, 'user_id' => rand(1, 3), 'bet_team1' => rand(0, 5), 'bet_team2' => rand(0, 5), 'match_id' => rand(1, 7)));
		}
	}

}

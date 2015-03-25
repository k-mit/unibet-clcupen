@extends('app')

@section('content')
    <div id="main" class="container">
        <main class="row">
            <div class="col-xs-4 full-height">
                <div class="row comp-info">
                    <div class="col-xs-8">
                        <h1 class="cl-logo">CL-CUPEN</h1>
                    </div>

                </div>
                <br>
                @foreach($facebook_user as $var)
                    {{$var}}<br>
                @endforeach
                @foreach($fbFriends as $var)
                    {{$var}}<br>
                @endforeach
                <br/>
                <br/>
                <br/>
                @foreach($active_round[0]['matches'] as $match)
                    {{$match['match_time']}}<img src="{!!$match['team1']['logo_path']!!}" width="25" height="25"><img src="{!!$match['team1']['country_flag_path']!!}">{{$match['team1']['team_name']}}-<img src="{!!$match['team2']['logo_path']!!}" width="25" height="25"><img src="{!!$match['team2']['country_flag_path']!!}">{{$match['team2']['team_name']}}<br>
                @endforeach

            </div>
            <div class="col-xs-8 full-height">
                <div class="row tool-boxes">
                    <div class="col-xs-6 tool">
                        <ul class="row tabrow">
                            <li class="col-xs-4">
                                <a href="#">10 Tips</a>
                            </li>
                            <li class="col-xs-4 active">
                                <a href="#">Form</a>
                            </li>
                            <li class="col-xs-4">
                                <a href="#">Glenns&nbsp;Tips</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div class="col-xs-12 tabcontent">

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 tool">
                        <ul class="row tabrow">
                            <li class="col-xs-6">
                                <a href="#">Alla</a>
                            </li>
                            <li class="col-xs-6 active">
                                <a href="#">Vänner</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div class="col-xs-12 tabcontent">
                                @foreach($highscoreAll as $highscoreAll_row)
                                    {{$highscoreAll_row->score}} {{$highscoreAll_row->user->name}}<br/>
                                @endforeach
                                <br>Highscore Friends:<br>
                                @foreach($highscoreFriends as $highscoreAll_row)
                                    {{$highscoreAll_row->score}} {{$highscoreAll_row->user->name}}<br/>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row party-box glenn-bg">
                    <div class="col-xs-12">

                    </div>

                </div>

            </div>
        </main>
    </div>

@endsection
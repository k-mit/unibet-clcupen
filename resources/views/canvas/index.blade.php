@extends('app')

@section('content')
    <div id="main" class="container">
        <main class="row">
            <div class="col-sm-12 col-md-4 full-height">
                <div class="comp-info">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="cl-logo">CL-CUPEN</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p>
                                <strong>Hur funkar det?</strong> omnis nis iste natus error sit voluptatem accusantium
                                doloremque laudantium, totam rem aperiam, ab illo inventore veritatis et quasi
                                architecto
                                beatae vitae
                            </p>
                        </div>
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
                <div class="game-table">
                    @foreach($active_round[0]['matches'] as $key=>$match)
                        <div class="match-row">
                            <div class="match-teams">
                                {{$match['team1']['team_name']}} - {{$match['team2']['team_name']}}
                            </div>
                            <div class="match-result">
                                <input size="2" class="match-result-input" name="team1"/> - <input size="2" class="match-result-input" name="team2"/>
                            </div>
                        </div>
                        {{--{{$match['match_time']}}--}}
                            {{--<img src="{!!$match['team1']['logo_path']!!}" width="25" height="25"><img--}}
                                {{--src="{!!$match['team1']['country_flag_path']!!}">{{$match['team1']['team_name']}}-<img--}}
                                {{--src="{!!$match['team2']['logo_path']!!}" width="25" height="25"><img--}}
                                {{--src="{!!$match['team2']['country_flag_path']!!}">{{$match['team2']['team_name']}}<br>--}}
                    @endforeach

                </div>

            </div>
            <div class="col-sm-12 col-md-8  full-height">
                <div class="row tool-boxes">
                    <div class="col-xs-12 col-sm-6 tool">
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
                    <div class="col-xs-12 col-sm-6 tool">
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
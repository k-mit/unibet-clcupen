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
                                {!!$page->snippet('hur_funkar_det')!!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="debug" style="display:none">
                    @foreach($facebook_user as $var)
                        {{$var}}<br>
                    @endforeach
                    @foreach($fbFriends as $var)
                        {{$var}}<br>
                    @endforeach
                    <br/>
                    <br/>
                    {{$tiebreaker_done}}
                </div>

                <div class="game-table">
                    <div class="game-table-header">
                        <h3 class="game_header">{!!$page->snippet('tippa_rubrik')!!}</h3>
                        <span class="game_sub_header">{!!$page->snippet('tippa_rubrik_ingress')!!}</span>
                    </div>
                    <hr/>
                    @foreach($active_round[0]['matches'] as $key=>$match)
                        <div class="match-row">
                            <div class="match-teams">
                                <a href="#" class="team-name" data-stats="{{$page->snippet($match['team1']['team_name'].' statistik')}}" data-teamid="{{$match['team1']['id']}}">{{$match['team1']['team_name']}}</a> -  <a href="#" class="team-name" data-stats="{{$page->snippet($match['team2']['team_name'].' statistik')}}" data-teamid="{{$match['team2']['id']}}">{{$match['team2']['team_name']}}</a>
                            </div>
                            <div class="match-result">
                                <input size="2" class="match-result-input" name="team1"/>

                                <div class="divider"></div>
                                <input size="2" class="match-result-input" name="team2"/>
                            </div>
                        </div>
                        {{--{{$match['match_time']}}--}}
                        {{--<img src="{!!$match['team1']['logo_path']!!}" width="25" height="25"><img--}}
                        {{--src="{!!$match['team1']['country_flag_path']!!}">{{$match['team1']['team_name']}}-<img--}}
                        {{--src="{!!$match['team2']['logo_path']!!}" width="25" height="25"><img--}}
                        {{--src="{!!$match['team2']['country_flag_path']!!}">{{$match['team2']['team_name']}}<br>--}}
                    @endforeach
                    <hr/>
                    <div class="tiebreaker">
                        <span class="game_sub_header">{{$page->snippet('tiebreaker_ingress')}}</span>

                        <h3 class="tiebreaker_header">{{$page->snippet('tiebreaker_rubrik')}}</h3>
                        <input size="2" class="match-result-input" name="tiebreaker">
                    </div>
                    <div class="game-table-footer"></div>
                </div>

            </div>
            <div class="col-sm-12 col-md-8  full-height">
                <div class="row tool-boxes">
                    <div class="col-xs-12 col-sm-6 tool">
                        <ul class="row tabrow">
                            <li class="col-xs-4 active">
                                <a href="#tips10">10 Tips</a>
                            </li>
                            <li class="col-xs-4 ">
                                <a href="#teamform">Form</a>
                            </li>
                            <li class="col-xs-4">
                                <a href="#glennstips">Glenns&nbsp;Tips</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div id="tips10" class="col-xs-12 tabcontent ">
                                {!!$page->snippet('10_tips')!!}<br><br>
                            </div>
                            <div id="teamform" class="col-xs-12 tabcontent active">
                                <h3><span class="team1">{{$active_round[0]['matches'][0]["team1"]["team_name"]}}</span> - <span class="team2 t2">{{$active_round[0]['matches'][0]["team2"]["team_name"]}}</span></h3>
                                <div id="statistics">
                                <div class="grid"><?php $team1 = explode(',',$page->snippet($active_round[0]['matches'][0]["team1"]["team_name"].' statistik'));?>
                                    <ul class="g desc {{$team1[0]}}">
                                        <li><span>V</span></li>
                                        <li><span>O</span></li>
                                        <li><span>F</span></li>
                                        <li></li>
                                    </ul>

                                   @for($i=0;$i<10;$i++)
                                        <ul class="g {{$i<9?$team1[$i+1]:''}}">
                                            <li></li><li></li><li></li><li></li>
                                        </ul>
                                    @endfor
                                </div>
                                <div class="grid t2"><?php $team2 = explode(',',$page->snippet($active_round[0]['matches'][0]["team2"]["team_name"].' statistik'));?>
                                    @for($i=0;$i<11;$i++)
                                        <ul class="g t2 {{$i<10?$team2[$i]:''}}"></ul>
                                    @endfor
                                </div>
                                </div>
                            </div>
                            <div id="glennstips" class="col-xs-12 tabcontent">
                                {!!$page->snippet('glenns_tips')!!}<br>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 tool">
                        <ul class="row tabrow">
                            <li class="col-xs-6">
                                <a href="#highscoreGlobal">Alla</a>
                            </li>
                            <li class="col-xs-6 active">
                                <a href="#highscoreFriends">Vänner</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div id="highscoreFriends" class="col-xs-12 tabcontent active">
                                @foreach($highscoreFriends as $hs_key => $highscoreFriends_row)
                                    @if(is_numeric($hs_key))
                                        {{$highscoreFriends_row->num}} {{$highscoreFriends_row->user_name}} {{$highscoreFriends_row->total_score}}
                                        <br/>
                                    @endif
                                @endforeach
                                JAG: {{$highscoreFriends['me']->num}} {{$highscoreFriends['me']->user_name}} {{$highscoreFriends['me']->total_score}}
                            </div>
                            <div id="highscoreGlobal" class="col-xs-12 tabcontent">
                                @foreach($highscoreAll as $hs_key => $highscoreAll_row)
                                    @if(is_numeric($hs_key))
                                        {{$highscoreAll_row->num}} {{$highscoreAll_row->user_name}} {{$highscoreAll_row->total_score}}
                                        <br/>
                                    @endif
                                @endforeach
                                {{$highscoreAll['me']->num}} {{$highscoreAll['me']->user_name}} {{$highscoreAll['me']->total_score}}
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
@extends('app')

@section('content')
    <div id="main" class="container">
        <main class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 full-height">
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
                <div class="debug">
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

                <form method="post" action="/saveBet">
                    <div class="game-table">
                        <input type="hidden" name="user_id" value="{{$facebook_user['user_id']}}">

                        <div class="game-table-header">
                            <h3 class="game_header">{!!$page->snippet('tippa_rubrik')!!}</h3>
                            <span class="game_sub_header">{!!$page->snippet('tippa_rubrik_ingress')!!}</span>
                        </div>
                        <hr/>
                        @foreach($active_round[0]['matches'] as $key=>$match)

                            <div class="match-row">
                                <input type="hidden" name="match_id_{{$key+1}}" value="{{$match['id']}}"/>

                                <div class="match-teams">
                                    <a href="#" class="team-name"
                                       data-stats="{{$page->snippet($match['team1']['team_name'].' statistik')}}"
                                       data-teamid="{{$match['team1']['id']}}">{{$match['team1']['team_name']}}</a>
                                    -
                                    <a href="#" class="team-name"
                                       data-stats="{{$page->snippet($match['team2']['team_name'].' statistik')}}"
                                       data-teamid="{{$match['team2']['id']}}">{{$match['team2']['team_name']}}</a>
                                </div>
                                <div class="match-result">
                                    <?php
                                    $matchbet = $bets->where('match_id', $match['id'])->first();
                                    ?>

                                    <input size="2" required tabindex="1"
                                           {{isset($matchbet->bet_team1)?'disabled ':''}}class="match-result-input"
                                           name="bet_team1_{{$key+1}}"
                                           value="{{isset($matchbet->id)?$matchbet->bet_team1:''}}"/>

                                    <div class="divider"></div>
                                    <input size="2" required tabindex="1"
                                           {{isset($matchbet->bet_team1)?'disabled ':''}}class="match-result-input"
                                           name="bet_team2_{{$key+1}}"
                                           value="{{isset($matchbet->id)?$matchbet->bet_team2:''}}"/>
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

                            <div class="input-group">
                                <h3 class="tiebreaker_header">{{$page->snippet('tiebreaker_rubrik')}}</h3>
                                <input size="2" required
                                       tabindex="1" {{isset($facebook_user['tiebreaker'])?'disabled ':''}}
                                       class="match-result-input" name="tiebreaker"
                                       value="{{isset($facebook_user['tiebreaker'])?$facebook_user['tiebreaker']:''}}">
                            </div>
                        </div>
                        <div class="game-table-footer"></div>
                    </div>
                    <div class="game-table-buttons">
                        <button class="game-button invite-friend">
                            <span>UTMANA EN VÄN</span>
                        </button>
                        <button type="submit" class="game-button">
                            <span>LÄMNA IN</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-8 full-height right-col">
                <div class="row tool-boxes">
                    <div class="col-xs-12 col-lg-6 tool">
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
                        <ul class="row tabrow only-medium">
                            <li class="col-xs-6">
                                <a href="#highscoreGlobal1">Highscore Alla</a>
                            </li>
                            <li class="col-xs-6">
                                <a href="#highscoreFriends1">Highscore Vänner</a>
                            </li>
                        </ul>


                        <div class="row contentrow">
                            <div id="tips10" class="col-xs-12 tabcontent active">
                                {!!$page->snippet('10_tips')!!}<br><br>
                            </div>
                            <div id="teamform" class="col-xs-12 tabcontent">
                                <h3><span class="team1">{{$active_round[0]['matches'][0]["team1"]["team_name"]}}</span>
                                    -
                                    <span class="team2 t2">{{$active_round[0]['matches'][0]["team2"]["team_name"]}}</span>
                                </h3>

                                <div id="statistics">
                                    <div class="grid"><?php $team1 = explode(',', $page->snippet($active_round[0]['matches'][0]["team1"]["team_name"] . ' statistik'));?>
                                        <ul class="g desc {{$team1[0]}}">
                                            <li><span>V</span></li>
                                            <li><span>O</span></li>
                                            <li><span>F</span></li>
                                            <li></li>
                                        </ul>

                                        @for($i=0;$i<10;$i++)
                                            <ul class="g {{$i<9?$team1[$i+1]:''}}">
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                        @endfor
                                    </div>
                                    <div class="grid t2"><?php $team2 = explode(',', $page->snippet($active_round[0]['matches'][0]["team2"]["team_name"] . ' statistik'));?>
                                        @for($i=0;$i<11;$i++)
                                            <ul class="g t2 {{$i<10?$team2[$i]:''}}"></ul>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div id="glennstips" class="col-xs-12 tabcontent">
                                {!!$page->snippet('glenns_tips')!!}<br>
                            </div>
                            <div class="only-medium">
                                <div id="highscoreFriends1" class="col-xs-12 tabcontent">
                                    <ul class="userlist">
                                    @foreach($highscoreFriends as $hs_key => $highscoreFriends_row)
                                            @if(is_numeric($hs_key))
                                                <li class="userrow">
                                                    <div class="placement">
                                                        {{$highscoreFriends_row->num}}
                                                    </div>
                                                    <div class="userimage">
                                                        <img src="https://graph.facebook.com/{{$highscoreFriends_row->id}}/picture">
                                                    </div>
                                                    <div class="username">
                                                        {{$highscoreFriends_row->user_name}}
                                                    </div>
                                                    <div class="userscore">
                                                        {{$highscoreFriends_row->total_score}}
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div id="highscoreGlobal1" class="col-xs-12 tabcontent">
                                    <ul class="userlist">
                                    @foreach($highscoreAll as $hs_key => $highscoreAll_row)
                                        @if(is_numeric($hs_key))
                                                <li class="userrow">
                                                    <div class="placement">
                                                        {{$highscoreAll_row->num}}
                                                    </div>
                                                    <div class="userimage">
                                                        <img src="https://graph.facebook.com/{{$highscoreAll_row->id}}/picture">
                                                    </div>
                                                    <div class="username">
                                                        {{$highscoreAll_row->user_name}}
                                                    </div>
                                                    <div class="userscore">
                                                        {{$highscoreAll_row->total_score}}
                                                    </div>
                                                </li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tool-footer"></div>

                    </div>
                    <div class="col-xs-12 col-sm-6 tool not-medium">
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
                                <ul class="userlist">
                                    @foreach($highscoreFriends as $hs_key => $highscoreFriends_row)
                                    @if(is_numeric($hs_key))
                                        <li class="userrow">
                                            <div class="placement">
                                                {{$highscoreFriends_row->num}}
                                            </div>
                                            <div class="userimage">
                                                <img src="https://graph.facebook.com/{{$highscoreFriends_row->id}}/picture">
                                            </div>
                                            <div class="username">
                                                {{$highscoreFriends_row->user_name}}
                                            </div>
                                            <div class="userscore">
                                                {{$highscoreFriends_row->total_score}}
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                                </ul>
                            </div>
                            <div id="highscoreGlobal" class="col-xs-12 tabcontent">
                                <ul class="userlist">
                                @foreach($highscoreAll as $hs_key => $highscoreAll_row)
                                    @if(is_numeric($hs_key))
                                        <li class="userrow">
                                            <div class="placement">
                                                {{$highscoreAll_row->num}}
                                            </div>
                                            <div class="userimage">
                                                <img src="https://graph.facebook.com/{{$highscoreAll_row->id}}/picture">
                                            </div>
                                            <div class="username">
                                                {{$highscoreAll_row->user_name}}
                                            </div>
                                            <div class="userscore">
                                                {{$highscoreAll_row->total_score}}
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row party-box">
                    <div class="col-xs-12">
                        <iframe src="https://player.vimeo.com/video/26152501" width="500" height="281" frameborder="0"
                                webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>

                </div>

            </div>
        </main>
    </div>
    @if ($errors->any())
        {{--{{dd($errors)}}--}}
    @endif
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '{{config('laravel-facebook-sdk.facebook_config.app_id')}}',
                xfbml      : true,
                version    : 'v2.2',
                cookie     : true
            });
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    // the user is logged in and has authenticated your
                    // app, and response.authResponse supplies
                    // the user's ID, a valid access token, a signed
                    // request, and the time the access token
                    // and signed request each expire
                    var uid = response.authResponse.userID;
                    var accessToken = response.authResponse.accessToken;
                    console.log('doooit!')
                } else if (response.status === 'not_authorized') {
                    // the user is logged in to Facebook,
                    // but has not authenticated your app
                } else {
                    // the user isn't logged in to Facebook.
                }
            });    };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

@endsection
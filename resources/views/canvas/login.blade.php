@extends('app')

@section('content')
    <div id="main" class="container login">
        <main class="row">
            <div class="col-sm-12 col-md-6 full-height">
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

                    <div class="game-table">

                        <div class="game-table-header">
                            <h3 class="game_header">{!!$page->snippet('anslut_facebook_rubrik')!!}</h3>
                        </div>
                        <hr/>
                        <span class="">{!!$page->snippet('anslut_facebook_body')!!}</span>

                        <div class="game-table-footer"></div>
                    </div>
                    <div class="game-table-buttons single">
                        <button class="game-button facebook-login">
                            <span>ANSLUT MED FACEBOOK</span>
                        </button>
                    </div>
            </div>

            <div class="col-sm-12 col-md-6 full-height right-col">

                <div class="row party-box">
                    <div class="col-xs-12">
                        <a href="#terms"><img src="/img/glenn.png" alt="1:A pris, din egen CL-final för 50 000sek, Glenn inkluderad"></a>
                    </div>
                </div>

            </div>
            <div id="unibetlogo"></div>

            @include('canvas.terms')
        </main>
    </div>
    <script>
        document.getElementById("main").style.visibility = "hidden";
    </script>

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
                    console.log('is connected');
//                    window.location.reload();
//                } else if (response.status === 'not_authorized') {
                    // the user is logged in to Facebook,
                    // but has not authenticated your app
                } else {
                    document.getElementById("main").style.visibility = "visible";
                }
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endsection
@extends('app')

@section('content')
    <div id="main" class="container login">
        <main class="row">
            <div class="col-sm-12 ">
                <div class="game-table-buttons single">
                    <button class="game-button facebook-login">
                        <span>ANSLUT MED FACEBOOK</span>
                    </button>
                </div>
            </div>
            <div id="badge"></div>
        </main>
    </div>
    <script>
        document.getElementById("main").style.visibility = "hidden";
    </script>

    @if ($errors->any())
        {{--{{dd($errors)}}--}}
    @endif
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '{{config('laravel-facebook-sdk.facebook_config.app_id')}}',
                xfbml: true,
                version: 'v2.2',
                cookie: true
            });
            FB.getLoginStatus(function (response) {
                if (response.status === 'connected') {
                    // the user is logged in and has authenticated your
                    // app, and response.authResponse supplies
                    // the user's ID, a valid access token, a signed
                    // request, and the time the access token
                    // and signed request each expire
                    var uid = response.authResponse.userID;
                    var accessToken = response.authResponse.accessToken;
//                    console.log('is connected');
                    window.location.reload();
//                } else if (response.status === 'not_authorized') {
                    // the user is logged in to Facebook,
                    // but has not authenticated your app
                } else {
                    document.getElementById("main").style.visibility = "visible";
                }
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endsection
@extends('app')

@section('content')
    <div id="main" class="container terms">
        <main class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 full-height">
            </div>

            <div id="unibetlogo"></div>

            @include('canvas.terms')
        </main>
    </div>
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '{{config('laravel-facebook-sdk.facebook_config.app_id')}}',
                xfbml: true,
                version: 'v2.2',
                cookie: true
            });
            FB.Canvas.setAutoGrow();
            FB.getLoginStatus(function (response) {
                if (response.status === 'connected') {
                    // the user is logged in and has authenticated your
                    // app, and response.authResponse supplies
                    // the user's ID, a valid access token, a signed
                    // request, and the time the access token
                    // and signed request each expire
                    var uid = response.authResponse.userID;
                    var accessToken = response.authResponse.accessToken;
                } else if (response.status === 'not_authorized') {
                    // the user is logged in to Facebook,
                    // but has not authenticated your app
                } else {
                    // the user isn't logged in to Facebook.
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
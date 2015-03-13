@extends('app')

@section('content')
    <form id="reloadform" method="POST">
        <input type="hidden" name="signedRequest" value="" id="sr">
        <input type="hidden" name="expiresIn" value="" id="ei">
        <input type="hidden" name="accessToken" value="" id="at">
        <input type="hidden" name="userId" value="" id="ui">
    </form>
    <!-- Scripts -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '1445118565779083',
                xfbml      : true,
                version    : 'v2.2'
            });
            FB.login(function(response) {
                if (response.authResponse) {
                    console.log(response);
                    $('#sr').val(response.signedRequest);
                    $('#ei').val(response.expiresIn);
                    $('#at').val(response.accessToken);
                    $('#ui').val(response.userId);
                    $('#reloadform').submit();
                } else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            },{scope: 'email'});
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unibet CL-Cupen | Vinn din egen CL-final med Glenn</title>
    <meta property="og:image" content="https://unibet-clcup.k-mit.se/img/clcup-share.png">
    <meta property="og:url" content="https://apps.facebook.com/cl-cupen/">
    <meta property="og:title"  content="Vinn din egen CL-final med Glenn" />
    <meta property="og:description" content="Under slutspelet i Champions League kan du tillsammans med oss tippa rätt resultat i kvartsfinalerna och semifinalerna. Vinnaren kommer få sin egen drömfinal värd 50.000 kr med Glenn Hysén" />
    <link href="/css/app.css?ts=1428915892" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@if (Session::has('flash_message'))
<div class="alert alert-success">{{Session::get('flash_message')}}</div>
@endif

@yield('content')


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fitvids/1.1.0/jquery.fitvids.min.js"></script>
<script src="/js/main.js?ts=1428915892"></script>

</body>
</html>

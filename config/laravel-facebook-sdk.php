<?php

return [
    /*
     * In order to integrate the Facebook SDK into your site,
     * you'll need to create an app on Facebook and enter the
     * app's ID and secret here.
     *
     * Add an app: https://developers.facebook.com/apps
     *
     * You can add additional config options here that are
     * available on the main Facebook\Facebook super service.
     *
     * https://github.com/facebook/facebook-php-sdk-v4/blob/master/src/Facebook/Facebook.php#L132
     */
    'facebook_config' => [
        'app_id' => env('FB_APP_ID', '1456225654668374'),
        'app_secret' => env('FB_APP_SECRET','d692d76c6268b923cebd0e5050728ed6'),
        'default_graph_version' => 'v2.2',
        //'enable_beta_mode' => true,
        //'http_client_handler' => 'guzzle',
    ],

    /*
     * The default list of permissions that are
     * requested when authenticating a new user with your app.
     * The fewer, the better! Leaving this empty is the best.
     * You can overwrite this when creating the login link.
     *
     * Example:
     *
     * 'default_scope' => ['email', 'user_birthday'],
     *
     * For a full list of permissions see:
     *
     * https://developers.facebook.com/docs/facebook-login/permissions
     */
    'default_scope' => [],

    /*
     * The default endpoint that Facebook will redirect to after
     * an authentication attempt.
     */
    'default_redirect_uri' => '/facebook/callback',
    ];

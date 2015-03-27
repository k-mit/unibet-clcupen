<?php

return [
    /**
     *  Define the endpoints for facebook exceptions
     *  Please provide full namespace for controller-paths
     */
//    'login' => 'App\Http\Controllers\FacebookController@viewLogin',
    'login' => function () {
        return view('canvas.login');
    },
    'reauth' => 'App\Http\Controllers\FacebookController@viewReauth'
];

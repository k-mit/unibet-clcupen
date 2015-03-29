<?php

return [
    /**
     *  Define the endpoints for facebook exceptions
     *  Please provide full namespace for controller-paths
     */
//    'login' => function () {
//        return view('canvas.login');
//    },
    'login' => 'App\Http\Controllers\FacebookController@viewLogin',
    'reauth' => 'App\Http\Controllers\FacebookController@viewReauth',
    'notadmin' => function () {
        return "Not authorized";
    }
];

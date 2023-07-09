<?php

Route::group([

    'namespace' => 'Helious\SeatBeacons\Http\Controllers',
    'prefix' => 'beacons',
    'middleware' => [
        'web',
        'auth',
        'can:seat-beacons.access',
    ],
], function()
{

    Route::get('/', [
        'uses' => 'BeaconsController@index',
        'as' => 'seat-beacons::index',
    ]);

});
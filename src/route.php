<?php

Route::group([

    'namespace' => 'Helious\SeatBeacons\Http\Controllers',
    'prefix' => 'beacons',
    'middleware' => [
        'web',
        'auth',
    ],
], function()
{

    Route::get('/', [
        'uses' => 'BeaconsController@index',
        'as' => 'seat-beacons::index',
        'middleware' => 'can:character.sheet,character',
    ]);

});
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', ['uses' => 'PageController@home']);

$app->get('/api/v1/exchange-rates/{from_iso}/{to_iso}', ['uses' => 'ExchangeRatesController@show']);
$app->post('/api/v1/exchange-rates/{from_iso}/{to_iso}', ['uses' => 'ExchangeRatesController@update']);

$app->post('/api/v1/auto-trade/', ['uses' => 'AutoTraderController@trade']);
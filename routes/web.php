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

Route::get('/', ['uses' => 'PageController@home']);

Route::get('/api/v1/exchange-rates', ['uses' => 'ExchangeRatesController@index']);
Route::get('/api/v1/exchange-rates/{from_iso}/{to_iso}', ['uses' => 'ExchangeRatesController@show']);
Route::post('/api/v1/exchange-rates/{from_iso}/{to_iso}', ['uses' => 'ExchangeRatesController@update']);
Route::get('/api/v1/exchange-rates/bulk-update', ['uses' => 'ExchangeRatesController@bulkUpdate']);

Route::post('/api/v1/auto-trade/', ['uses' => 'AutoTraderController@trade']);
Route::get('/api/v1/order-details/{order_id}', ['uses' => 'AutoTraderController@orderDetails']);

Route::get('/api/v1/xbt-gap', ['uses' => 'ExchangeRateGapController@index']);
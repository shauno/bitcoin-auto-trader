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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/api/v1/exchange-rates/{from_iso}/{to_iso}', ['uses' => 'ExchangeRatesController@update']);
$app->get('/api/v1/auto-trade/', ['uses' => 'AutoTraderController@trade']);

$app->get('accounts', function() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mybitx.com/api/1/balance');
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_USERPWD, env('BITX_KEY').":".env('BITX_SECRET'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
});

$app->get('create-account', function() {
//	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://api.mybitx.com/api/1/accounts');
//	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
//	curl_setopt($ch, CURLOPT_USERPWD, env('BITX_KEY').":".env('BITX_SECRET'));
//	curl_setopt($ch, CURLOPT_POST, true);
//	curl_setopt($ch, CURLOPT_POSTFIELDS, 'currency=ZAR&name=Auto%20%Trader%20ZAR');
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//	$data = curl_exec($ch);
//	curl_close($ch);
//
//	return $data;
});

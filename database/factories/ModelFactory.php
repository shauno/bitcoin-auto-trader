<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(\BtcAutoTrader\ExchangeRates\ExchangeRate::class, function() {
    return [
        'id' => 1,
        'from_iso' => 'USD',
        'to_iso' => 'ZAR',
        'rate' => 13,
        'tracker_url' => 'http://example.com',
        'value_key' => 'some.value.key',
        'created_at' => '2017-03-14 20:01:14',
        'updated_at' => '2017-03-14 20:06:14',
    ];
});

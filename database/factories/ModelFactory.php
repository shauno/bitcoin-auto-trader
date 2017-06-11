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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
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

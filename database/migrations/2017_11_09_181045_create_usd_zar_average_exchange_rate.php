<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsdZarAverageExchangeRate extends Migration
{
    /**
     * Run the migrations.failed sa
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->string('from_iso', 6)->change();
        });

        Schema::table('exchange_rate_logs', function (Blueprint $table) {
            $table->string('from_iso', 6)->change();
        });

        $rate = (new \BtcAutoTrader\ExchangeRates\ExchangeRate())
            ->guard([])
            ->fill([
                'from_iso' => 'USDZAR',
                'to_iso' => 'GAP',
                'rate' => 0,
                'tracker_url' => getenv('APP_URL').'/usd-zar-rolling-average',
                'value_key'  => 'percent',
            ])->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DELETE FROM `exchange_rates` WHERE `from_iso` = "USDZAR"');

        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->string('from_iso', 3)->change();
        });

        //Can't change exchange_rate_logs.from_iso as records will already have been inserted
    }
}

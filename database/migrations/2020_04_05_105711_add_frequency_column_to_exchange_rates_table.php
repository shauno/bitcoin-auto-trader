<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFrequencyColumnToExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->integer('frequency')
                ->after('value_key')
                ->default(1);
        });

        \DB::statement('UPDATE `exchange_rates` SET `frequency` = 60 WHERE from_iso = "USD" AND to_iso = "ZAR"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });
    }
}

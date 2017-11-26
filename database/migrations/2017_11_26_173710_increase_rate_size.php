<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseRateSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rate_logs', function (Blueprint $table) {
            $table->decimal('rate', 16, 2)->change();
        });

        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->decimal('rate', 16, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rate_logs', function (Blueprint $table) {
            $table->decimal('rate', 8, 2)->change();
        });

        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->decimal('rate', 8, 2)->change();
        });
    }
}

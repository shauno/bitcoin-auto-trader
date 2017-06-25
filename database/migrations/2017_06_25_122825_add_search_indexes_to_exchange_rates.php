<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSearchIndexesToExchangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rate_logs', function (Blueprint $table) {
            $table->index('from_iso');
            $table->index('to_iso');
            $table->index('created_at');
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
            $table->dropIndex('from_iso');
            $table->dropIndex('to_iso');
            $table->dropIndex('created_at');
        });
    }
}

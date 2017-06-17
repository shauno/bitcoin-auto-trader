<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('creation_timestamp', false, true)->nullable()->change();
            $table->bigInteger('expiration_timestamp', false, true)->nullable()->change();
            $table->bigInteger('completed_timestamp', false, true)->nullable()->change();
            $table->bigInteger('completed_timestamp', false, true)->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->decimal('limit_price', 16, 8)->nullable()->change();
            $table->decimal('limit_volume', 16, 8)->nullable()->change();
            $table->decimal('base', 16, 8)->nullable()->change();
            $table->decimal('counter', 16, 8)->nullable()->change();
            $table->decimal('fee_base', 16, 8)->nullable()->change();
            $table->decimal('fee_counter', 16, 8)->nullable()->change();
            $table->string('pair')->nullable()->change();
            $table->mediumText('trades')->nullable()->change();
            $table->decimal('btc', 16, 8)->nullable()->change();
            $table->decimal('zar', 16, 8)->nullable()->change();
            $table->decimal('fee_btc', 16, 8)->nullable()->change();
            $table->decimal('fee_zar', 16, 8)->nullable()->change();
            $table->decimal('rate', 8, 2)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // no need to go back, it was wrong before :/
        });
    }
}

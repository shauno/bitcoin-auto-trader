<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bitx_order_id', 255);
            $table->integer('creation_timestamp', false, true);
            $table->integer('expiration_timestamp', false, true);
            $table->integer('completed_timestamp', false, true);
            $table->enum('type', ['ASK', 'BID', 'BUY']);
            $table->string('state');
            $table->decimal('limit_price', 16, 8);
            $table->decimal('limit_volume', 16, 8);
            $table->decimal('base', 16, 8);
            $table->decimal('counter', 16, 8);
            $table->decimal('fee_base', 16, 8);
            $table->decimal('fee_counter', 16, 8);
            $table->string('pair');
            $table->mediumText('trades');
            $table->decimal('btc', 16, 8);
            $table->decimal('zar', 16, 8);
            $table->decimal('fee_btc', 16, 8);
            $table->decimal('fee_zar', 16, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

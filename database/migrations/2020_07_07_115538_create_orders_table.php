<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('id', true);
            $table->integer('customer_id')->index('fk_orders_customers1_idx');
            $table->string('reference', 500);
            $table->dateTime('date');
            $table->integer('payment_type_vp');
            $table->integer('payment_state');
            $table->double('total');
            $table->double('coupon_value');
            $table->double('delivery_value');
            $table->integer('user_address_id')->index('fk_orders_user_addresses1_idx');
            $table->integer('order_state');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->integer('state')->default(1);
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

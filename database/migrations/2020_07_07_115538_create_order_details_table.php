<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->index('fk_order_details_products1_idx');
            $table->integer('order_id')->index('fk_order_details_orders1_idx');
            $table->string('name', 500);
            $table->double('value');
            $table->integer('quantity');
            $table->double('total_value');
            $table->string('observation', 500)->nullable();
            $table->longText('product_config')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}

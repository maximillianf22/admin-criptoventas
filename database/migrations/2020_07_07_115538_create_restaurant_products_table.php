<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->index('fk_restaurant_product_products1_idx');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->double('value');
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
        Schema::dropIfExists('restaurant_products');
    }
}

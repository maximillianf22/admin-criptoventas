<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->index('fk_market_product_products1_idx');
            $table->integer('unit_id')->index('fk_market_products_units1_idx');
            $table->string('variation_name', 500)->nullable();
            $table->integer('parent')->nullable();
            $table->integer('mininum_unit')->nullable();
            $table->integer('quantity_content')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
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
        Schema::dropIfExists('market_products');
    }
}

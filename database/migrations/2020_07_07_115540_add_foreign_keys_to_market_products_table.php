<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMarketProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_products', function (Blueprint $table) {
            $table->foreign('product_id', 'fk_market_product_products1')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('unit_id', 'fk_market_products_units1')->references('id')->on('units')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_products', function (Blueprint $table) {
            $table->dropForeign('fk_market_product_products1');
            $table->dropForeign('fk_market_products_units1');
        });
    }
}

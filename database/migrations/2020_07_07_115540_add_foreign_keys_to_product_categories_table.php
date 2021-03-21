<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreign('category_id', 'fk_product_categories_categories1')->references('id')->on('categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('product_id', 'fk_product_categories_products1')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign('fk_product_categories_categories1');
            $table->dropForeign('fk_product_categories_products1');
        });
    }
}

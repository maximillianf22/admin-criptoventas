<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToIngredientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ingredient_categories', function (Blueprint $table) {
            $table->foreign('restaurant_product_id', 'fk_ingredient_categories_restaurant_products1')->references('id')->on('restaurant_products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredient_categories', function (Blueprint $table) {
            $table->dropForeign('fk_ingredient_categories_restaurant_products1');
        });
    }
}

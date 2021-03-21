<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->foreign('commerces_id', 'fk_table1_commerces1')->references('id')->on('commerces')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('products_id', 'fk_table1_products2')->references('id')->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->dropForeign('fk_table1_commerces1');
            $table->dropForeign('fk_table1_products2');
        });
    }
}

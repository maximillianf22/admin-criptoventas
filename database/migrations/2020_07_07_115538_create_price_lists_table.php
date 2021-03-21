<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('commerces_id')->index('fk_table1_commerces1_idx');
            $table->integer('products_id')->index('fk_table1_products2_idx');
            $table->integer('profile_vp');
            $table->double('value');
            $table->integer('min');
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
        Schema::dropIfExists('price_lists');
    }
}

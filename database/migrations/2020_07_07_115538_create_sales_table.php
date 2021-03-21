<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->index('fk_table1_products1_idx');
            $table->double('actual_price');
            $table->double('before_price');
            $table->double('discount')->nullable();
            $table->integer('sold')->nullable()->default(0);
            $table->dateTime('max_date')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->integer('validity_type_vp');
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
        Schema::dropIfExists('sales');
    }
}

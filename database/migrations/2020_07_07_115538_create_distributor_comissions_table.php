<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorComissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_comissions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id')->index('fk_table1_orders1_idx');
            $table->integer('distributor_id')->index('fk_table1_customers1_idx');
            $table->string('distributor_code', 500);
            $table->double('distributor_percent');
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
        Schema::dropIfExists('distributor_comissions');
    }
}

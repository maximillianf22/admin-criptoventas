<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDistributorComissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distributor_comissions', function (Blueprint $table) {
            $table->foreign('distributor_id', 'fk_table1_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('order_id', 'fk_table1_orders1')->references('id')->on('orders')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distributor_comissions', function (Blueprint $table) {
            $table->dropForeign('fk_table1_customers1');
            $table->dropForeign('fk_table1_orders1');
        });
    }
}

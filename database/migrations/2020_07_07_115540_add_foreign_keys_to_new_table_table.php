<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNewTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_table', function (Blueprint $table) {
            $table->foreign('commerce_category_id', 'commerce_category_id')->references('id')->on('commerce_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('commerce_id', 'commerce_id')->references('id')->on('commerces')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_table', function (Blueprint $table) {
            $table->dropForeign('commerce_category_id');
            $table->dropForeign('commerce_id');
        });
    }
}

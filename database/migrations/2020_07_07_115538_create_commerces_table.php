<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommercesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commerces', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_commerces_users1_idx');
            $table->string('bussiness_name', 500);
            $table->string('nit', 500);
            $table->integer('commerce_type_vp');
            $table->tinyInteger('is_opened')->default(1);
            $table->integer('delivery_config');
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
        Schema::dropIfExists('commerces');
    }
}

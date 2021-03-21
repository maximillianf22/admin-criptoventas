<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_customers_users1_idx');
            $table->integer('profile_vp');
            $table->integer('distributor_id')->nullable()->index('fk_customers_customers1_idx');
            $table->string('distributor_code', 500)->nullable();
            $table->double('distributor_percent')->nullable();
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
        Schema::dropIfExists('customers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('module_id')->index('fk_actions_modules1_idx');
            $table->string('name', 500);
            $table->string('route', 500)->nullable();
            $table->integer('parent')->nullable();
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
        Schema::dropIfExists('actions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRangeHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_hours', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('week_day');
            $table->time('init_hour');
            $table->time('fin_hour');
            $table->integer('limit');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->integer('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('range_hours');
    }
}

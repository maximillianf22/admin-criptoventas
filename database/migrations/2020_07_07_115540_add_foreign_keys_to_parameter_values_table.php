<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToParameterValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parameter_values', function (Blueprint $table) {
            $table->foreign('parameter_id', 'fk_parameter_values_parameters')->references('id')->on('parameters')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parameter_values', function (Blueprint $table) {
            $table->dropForeign('fk_parameter_values_parameters');
        });
    }
}

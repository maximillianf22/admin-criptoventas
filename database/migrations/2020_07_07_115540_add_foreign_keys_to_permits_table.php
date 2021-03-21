<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->foreign('action_id', 'fk_permits_actions1')->references('id')->on('actions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('rol_id', 'fk_permits_roles1')->references('id')->on('roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropForeign('fk_permits_actions1');
            $table->dropForeign('fk_permits_roles1');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('document', 500)->unique('document_UNIQUE');
            $table->integer('document_type_vp');
            $table->string('name', 500);
            $table->string('last_name', 500);
            $table->string('photo', 500)->default('default.png');
            $table->string('email', 500)->nullable();
            $table->string('cellphone', 500);
            $table->string('password', 500);
            $table->integer('rol_id')->index('fk_users_roles1_idx');
            $table->string('code', 500)->nullable();
            $table->integer('user_state')->default(1);
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationsAtUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->bigInteger('rol_id')->unsigned()->nullable()->after('code');
            $table->bigInteger('departament_id')->unsigned()->nullable()->after('rol_id');

            // Relaciones en migracion
            $table->foreign('rol_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('departament_id')->references('id')->on('departamentos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

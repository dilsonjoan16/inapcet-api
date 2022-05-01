<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('state');
            $table->bigInteger('user_created')->unsigned()->nullable();
            $table->bigInteger('user_updated')->unsigned()->nullable();
            $table->bigInteger('user_deleted')->unsigned()->nullable();
            $table->bigInteger('user_restored')->unsigned()->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('restored_at')->nullable();
            // Relaciones en migraciones

            $table->foreign('user_created')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_updated')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_deleted')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_restored')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}

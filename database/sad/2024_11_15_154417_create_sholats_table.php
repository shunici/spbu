<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSholatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sholats', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->string('nama')->nullable();
            $table->unsignedBigInteger('imam')->nullable();
            $table->unsignedBigInteger('imam_pengganti')->nullable();
            $table->unsignedBigInteger('muadzin')->nullable();
            $table->unsignedBigInteger('muadzin_pengganti')->nullable();
            $table->timestamps();

            $table->foreign('imam')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('imam_pengganti')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('muadzin')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('muadzin_pengganti')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sholats');
    }
}

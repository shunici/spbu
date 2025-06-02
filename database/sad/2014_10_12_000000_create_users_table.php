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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('foto');
            $table->integer('nomor_urut')->default(0);
            $table->rememberToken();
            $table->boolean('status')->defalut(0);
            $table->string('alamat')->nullable();
            $table->string('hp')->nullable();
            $table->timestamps();

            
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')
            ->on('jabatans')->onDelete('cascade');


            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')
            ->on('roles')->onDelete('cascade');
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

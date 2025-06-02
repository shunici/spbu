<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembeliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->string('status_bayar')->nullable();
            $table->integer('total_bayar');
            $table->integer('sisa_bayar')->default(0);
            $table->integer('uang_masuk')->default(0);
            $table->integer('pajak')->default(0);
            $table->integer('diskon')->default(0);
            $table->dateTime('waktu')->nullable();

            
            $table->unsignedBigInteger('suplier_id');
            $table->foreign('suplier_id')->references('id')
            ->on('supliers')->onDelete('cascade');

            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelians');
    }
}

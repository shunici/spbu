<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKajiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kajians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kajian');
            $table->integer('posisi')->default(30);
            $table->string('hari')->nullable();
            $table->string('ket_hari')->nullable();
            $table->string('foto')->nullable(); //foto user bila tidak ada          
            $table->unsignedBigInteger('user_id');   //pemateri
            $table->string('bahasan')->nullable();     
            $table->date('tgl')->nullable();
            $table->time('awal')->nullable();
            $table->time('akhir')->nullable();
            $table->boolean('status')->nullable();
            $table->string('pesan_status')->nullable(); // hanya berfungsi ketika status acara false
            $table->boolean('rutin')->defatul(0); //jika rutin ditempatkan keseharian, jika tidak maka sesuai tgl waktu
            $table->string('keterangan')->nullable();
            $table->timestamps();

    
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kajians');
    }
}

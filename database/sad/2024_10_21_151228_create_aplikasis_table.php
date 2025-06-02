<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAplikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telp')->nullable();
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
            $table->string('foto4')->nullable();      
            $table->string('logo')->nullable();
            $table->string('kop')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kab_kota')->nullable();
            $table->string('peta_link')->nullable();
            $table->string('peta')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('email')->nullable();     
            $table->boolean('register_rule')->default(1);                  
            $table->string('sosmed')->nullable();
            $table->string('alert_judul')->nullable();
            $table->string('alert_isi')->nullable();
            $table->string('alert_warna')->nullable();
            $table->integer('alert_tampil')->default(0);
            $table->bigInteger('kunjungan')->default(0); // Tambah field kunjunga
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
        Schema::dropIfExists('aplikasis');
    }
}

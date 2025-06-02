<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->bigInteger('total')->default(0); 
            $table->bigInteger('kondisi_rusak')->default(0); 
            $table->bigInteger('kondisi_baik')->default(0);
            $table->bigInteger('harga')->default(0);
            $table->string('kategori')->nullable();
            $table->string('keterangan')->nullable();     
            $table->string('foto')->nullable();       
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
        Schema::dropIfExists('inventaris');
    }
}

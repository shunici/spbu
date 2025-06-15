<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            $table->text('uraian');             
            $table->string('toko')->nullable();  
            $table->integer('qty')->default(0); 
            $table->string('satuan')->nullable();   
            $table->integer('nominal')->default(0);     
            $table->integer('total')->default(0);             
            $table->text('keterangan')->nullable();    
            $table->date('tgl')->nullable();  
            $table->text('foto')->nullable();      
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
        Schema::dropIfExists('realisasis');
    }
}

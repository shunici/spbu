<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenyaluransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('penyalurans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_zakat');
             
            $table->integer('nominal')->default(0);       
            $table->float('kuantitas')->default(0);
            $table->string('satuan')->nullable();
            $table->string('foto')->nullable();             
            $table->string('keterangan')->nullable();      
            $table->text('ttd')->nullable();
            $table->unsignedBigInteger('barang_id');  
            $table->unsignedBigInteger('mustahik_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rekapitulasi_zakat_id');  
         
            $table->timestamps();

            $table->foreign('barang_id')->references('id')
            ->on('barangs')->onDelete('cascade');

            $table->foreign('mustahik_id')->references('id')
            ->on('mustahiks')->onDelete('cascade');

            
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');

            $table->foreign('rekapitulasi_zakat_id')->references('id')
            ->on('rekapitulasi_zakats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyalurans');
    }
}

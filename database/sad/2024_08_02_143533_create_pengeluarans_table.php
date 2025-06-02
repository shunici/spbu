<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluarans', function (Blueprint $table) {          
            $table->bigIncrements('id');
            $table->text('uraian')->nullable();
            $table->bigInteger('total')->default(0); 
            $table->string('text')->nullable();
            $table->unsignedBigInteger('kategori_id'); 
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rekapitulasi_id');
            $table->enum('kas', ['masjid', 'bank'])->default('masjid'); 
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')
            ->on('kategoris')->onDelete('cascade');

            
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');

            $table->foreign('rekapitulasi_id')->references('id')
            ->on('rekapitulasis')->onDelete('cascade');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengeluarans');
    }
}

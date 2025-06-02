<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {            
            $table->id();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('harga_jual')->default(0);
            $table->integer('harga_beli')->default(0);
            $table->integer('stok')->default(0);
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')
            ->on('kategoris')->onDelete('cascade');

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
        Schema::dropIfExists('barangs');
    }
}

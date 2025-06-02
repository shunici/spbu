<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembeliansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelian_id');                           
            $table->unsignedBigInteger('barang_id');

            $table->integer('jumlah')->default(1);       
            $table->integer('sub_total');
            $table->boolean('proses_gudang');               

            $table->foreign('pembelian_id')->references('id')
            ->on('pembelians')->onDelete('cascade');

            $table->foreign('barang_id')->references('id')
            ->on('barangs')->onDelete('cascade');

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
        Schema::dropIfExists('detail_pembelians');
    }
}

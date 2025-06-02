<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapitulasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekapitulasis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('keterangan')->nullable();
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('pemasukan')->default(0);
            $table->bigInteger('pengeluaran')->default(0);
            $table->bigInteger('saldo_akhir')->default(0);
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
        Schema::dropIfExists('rekapitulasis');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapitulasiZakatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekapitulasi_zakats', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan')->nullable();
            $table->bigInteger('saldo_awal')->default(0);
            $table->bigInteger('penerimaan')->default(0);
            $table->bigInteger('penyaluran')->default(0);
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
        Schema::dropIfExists('rekapitulasi_zakats');
    }
}

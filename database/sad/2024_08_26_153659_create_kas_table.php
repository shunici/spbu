<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->text('uraian')->nullable();
            $table->bigInteger('input')->default(0); 
            $table->bigInteger('masjid')->default(0); 
            $table->bigInteger('bank')->default(0); 
            $table->enum('jenis_aksi', ['masjid_ke_bank', 'bank_ke_masjid'])->nullable(); 
            $table->unsignedBigInteger('user_id');
            $table->string('history')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('perubahanMK')->detault(false);
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
        Schema::dropIfExists('kas');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('posisi', ['sidebar', 'footer']);
            $table->integer('jumlah')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('kontak')->nullable();
            $table->string('logo')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->integer('nomor')->default(0);
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
        Schema::dropIfExists('sponsors');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGajihsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gajihs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('keterangan');
            $table->integer('pengurangan')->default(0);
            $table->integer('penerimaan')->default(0);  
            $table->text('ket_penerimaan')->nullable();         
            $table->text('ket_pengurangan')->nullable();        
            $table->unsignedBigInteger('user_id');
            $table->boolean('sdh_terima')->default(0);
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
        Schema::dropIfExists('gajihs');
    }
}

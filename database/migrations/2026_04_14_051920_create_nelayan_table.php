<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNelayanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nelayan', function (Blueprint $table) {
            $table->bigIncrements('nelayan_id');
            $table->unsignedBigInteger('pengguna_id');
            $table->string('nama');
            $table->string('nomor_hp')->nullable(); // Menggunakan Nomor HP
            $table->timestamps();

            $table->foreign('pengguna_id')->references('pengguna_id')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nelayan');
    }
}

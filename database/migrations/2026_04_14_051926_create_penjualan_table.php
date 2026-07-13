<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->bigIncrements('penjualan_id');
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('nelayan_id');
            $table->date('tanggal');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->unsignedBigInteger('biaya_admin')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pengguna_id')->references('pengguna_id')->on('pengguna')->onDelete('cascade');
            $table->foreign('nelayan_id')->references('nelayan_id')->on('nelayan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}

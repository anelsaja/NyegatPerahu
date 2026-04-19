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
            // nama_pengepul dihapus dari sini
            $table->decimal('total_harga', 15, 2);
            $table->decimal('biaya_admin', 15, 2)->default(0); // Kolom Biaya Admin
            // $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas']);
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

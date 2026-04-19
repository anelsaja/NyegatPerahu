<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->bigIncrements('detail_id');
            $table->unsignedBigInteger('penjualan_id');
            $table->string('nama_pengepul'); // Pengepul pindah ke sini
            $table->string('jenis_hasil_laut');
            $table->decimal('harga', 15, 2);

            // TAMBAHKAN BARIS INI:
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas'])->default('Lunas');

            $table->foreign('penjualan_id')->references('penjualan_id')->on('penjualan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
}

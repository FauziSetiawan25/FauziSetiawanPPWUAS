<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel transaksi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key
            $table->date('tanggal_pembelian'); // Kolom untuk tanggal pembelian
            $table->decimal('total_harga', 10, 2); // Kolom total harga transaksi
            $table->decimal('bayar', 10, 2); // Kolom pembayaran
            $table->decimal('kembalian', 10, 2); // Kolom kembalian
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft delete
        });
    }

    /**
     * Balikkan perubahan yang dilakukan oleh migration ini.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi'); // Menghapus tabel jika rollback
    }
}
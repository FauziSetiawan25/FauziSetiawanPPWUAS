<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel transaksi.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pembelian');
            $table->integer('total_harga');
            $table->integer('bayar');
            $table->integer('kembalian');
            $table->timestamps();  // Menambahkan kolom created_at dan updated_at
            $table->softDeletes(); // Menambahkan kolom deleted_at untuk soft delete
        });
    }

    /**
     * Balikkan perubahan yang dilakukan oleh migration ini.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
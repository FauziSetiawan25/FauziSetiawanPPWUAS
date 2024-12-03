<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')
                  ->constrained('transaksi') // Menentukan tabel yang menjadi referensi
                  ->onDelete('cascade');    // Menghapus transaksi detail jika transaksi dihapus
            $table->string('nama_produk');
            $table->integer('harga_satuan');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
            $table->softDeletes(); // Menambahkan kolom deleted_at untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};

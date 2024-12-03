<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Bezhanov\Faker\Provider\Commerce;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TransaksiDetailSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new Commerce($faker));

        // Ambil semua transaksi yang ada
        $transaksi = Transaksi::all();

        foreach ($transaksi as $t) {
            // Gunakan Faker untuk menghasilkan jumlah detail antara 5 hingga 15
            $numberOfDetails = $faker->numberBetween(5, 15);

            $total_harga = 0;

            // Loop untuk membuat transaksi detail
            for ($j = 0; $j < $numberOfDetails; $j++) {
                $hargaSatuan = $faker->numberBetween(10, 500) * 100;  // Harga satuan antara 1.000 hingga 50.000
                $jumlah = $faker->numberBetween(1, 5);  // Jumlah produk antara 1 dan 5
                $subtotal = $hargaSatuan * $jumlah;  // Subtotal = harga * jumlah
                $total_harga += $subtotal;

                // Insert data detail transaksi
                TransaksiDetail::create([
                    'id_transaksi' => $t->id,
                    'nama_produk' => $faker->productName,  // Nama produk acak
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total_harga, bayar, dan kembalian pada transaksi
            $t->total_harga = $total_harga;
            $t->bayar = ceil($total_harga / 50000) * 50000;  // Pembayaran dibulatkan ke kelipatan 50.000
            $t->kembalian = $t->bayar - $total_harga;  // Kembalian = bayar - total_harga

            // Simpan perubahan pada transaksi
            $t->save();
        }
    }
}
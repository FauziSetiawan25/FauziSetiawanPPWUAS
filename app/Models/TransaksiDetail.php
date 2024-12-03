<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiDetail extends Model
{
    use HasFactory, SoftDeletes; // Menambahkan SoftDeletes trait untuk mendukung soft delete

    // Tentukan nama tabel jika berbeda dari nama default (plural dari nama model)
    protected $table = 'transaksi_detail';

    // Tentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'id_transaksi',
        'nama_produk',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    // Relasi dengan model Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id');
    }

    // Menambahkan deleted_at ke dalam array $dates agar bisa menangani soft deletes
    protected $dates = ['deleted_at'];
}
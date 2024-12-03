<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;  // Tambahkan ini

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;  // Menggunakan SoftDeletes

    protected $table = 'transaksi'; 

    protected $fillable = [
        'tanggal_pembelian',
        'total_harga',
        'bayar',
        'kembalian', // pastikan kolom ini ada jika digunakan
    ];

    protected $dates = ['deleted_at'];  // Menentukan kolom yang digunakan untuk soft delete

    public function transaksidetail()
    {
        return $this->hasMany(TransaksiDetail::class, 'id_transaksi', 'id');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah transaksi
        $transaksi_count = Transaksi::count();

        // Menghitung jumlah item terjual
        $jumlah_item_terjual = TransaksiDetail::sum('jumlah');

        // Menghitung total omzet
        $omzet = TransaksiDetail::sum('subtotal');

        return view('dashboard', compact('transaksi_count', 'jumlah_item_terjual', 'omzet'));
    }
}
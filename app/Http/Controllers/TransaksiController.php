<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        // Menampilkan transaksi terbaru terlebih dahulu
        $transaksi = Transaksi::orderBy('tanggal_pembelian', 'DESC')->get();

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'bayar' => 'required|numeric',
            'nama_produk1' => 'required|string',
            'harga_satuan1' => 'required|numeric',
            'jumlah1' => 'required|numeric',
            'nama_produk2' => 'required|string',
            'harga_satuan2' => 'required|numeric',
            'jumlah2' => 'required|numeric',
            'nama_produk3' => 'required|string',
            'harga_satuan3' => 'required|numeric',
            'jumlah3' => 'required|numeric',
        ]);

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Membuat transaksi baru
            $transaksi = new Transaksi();
            $transaksi->tanggal_pembelian = $request->input('tanggal_pembelian');
            $transaksi->total_harga = 0;
            $transaksi->bayar = $request->input('bayar');
            $transaksi->kembalian = 0;
            $transaksi->save();

            $total_harga = 0;
            $i = 1;
            
            // Loop untuk menambahkan detail transaksi
            while ($request->has('nama_produk' . $i)) {
                $transaksidetail = new TransaksiDetail();
                $transaksidetail->id_transaksi = $transaksi->id;
                $transaksidetail->nama_produk = $request->input('nama_produk' . $i);
                $transaksidetail->harga_satuan = $request->input('harga_satuan' . $i);
                $transaksidetail->jumlah = $request->input('jumlah' . $i);
                $transaksidetail->subtotal = $request->input('harga_satuan' . $i) * $request->input('jumlah' . $i);
                $transaksidetail->save();

                // Menambahkan subtotal ke total harga
                $total_harga += $transaksidetail->subtotal;
                $i++;
            }

            // Update total harga dan kembalian transaksi utama
            $transaksi->total_harga = $total_harga;
            $transaksi->kembalian = $transaksi->bayar - $total_harga;
            $transaksi->save();

            // Commit transaksi
            DB::commit();

            return redirect()->route('transaksidetail.index', ['id_transaksi' => $transaksi->id])
                             ->with('pesan', 'Berhasil menambahkan transaksi');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollback();
            return redirect()->back()->withErrors(['Transaction' => 'Gagal menambahkan data'])->withInput();
        }
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bayar' => 'required|numeric'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $total_harga = $transaksi->transaksidetail->sum('subtotal');
        $transaksi->bayar = $request->input('bayar');
        $transaksi->kembalian = $transaksi->bayar - $total_harga;
        $transaksi->save();

        return redirect()->route('transaksi.index')
                         ->with('pesan', 'Berhasil mengubah data transaksi');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete(); // Soft delete

        return redirect()->route('transaksi.index')
                         ->with('pesan', 'Berhasil menghapus transaksi');
    }
}
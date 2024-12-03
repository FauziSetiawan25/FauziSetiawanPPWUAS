<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiDetailController extends Controller
{
    // Menampilkan daftar transaksi detail
    public function index()
    {
        $transaksidetail = TransaksiDetail::with('transaksi')->orderBy('id','DESC')->get();

        return view('transaksidetail.index', compact('transaksidetail'));
    }

    // Menampilkan detail transaksi
    public function detail(Request $request)
    {
        $transaksi = Transaksi::with('transaksidetail')->findOrFail($request->id_transaksi);

        return view('transaksidetail.detail', compact('transaksi'));
    }

    // Mengedit transaksi detail
    public function edit($id)
    {
        $transaksidetail = TransaksiDetail::findOrFail($id);
        return view('transaksidetail.edit', compact('transaksidetail'));
    }

    // Memperbarui transaksi detail
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string',
            'harga_satuan' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        // Mencari transaksi detail yang akan diupdate
        $transaksidetail = TransaksiDetail::findOrFail($id);

        // Mulai transaksi DB
        DB::beginTransaction();

        try {
            // Update detail transaksi
            $transaksidetail->nama_produk = $request->input('nama_produk');
            $transaksidetail->harga_satuan = $request->input('harga_satuan');
            $transaksidetail->jumlah = $request->input('jumlah');
            $transaksidetail->subtotal = $transaksidetail->harga_satuan * $transaksidetail->jumlah;
            $transaksidetail->save();

            // Update total harga di transaksi
            $transaksi = Transaksi::findOrFail($transaksidetail->id_transaksi);
            $transaksi->total_harga = $transaksi->transaksidetail->sum('subtotal');
            $transaksi->kembalian = $transaksi->bayar - $transaksi->total_harga;
            $transaksi->save();

            // Commit transaksi DB
            DB::commit();

            // Redirect ke halaman detail transaksi
            return redirect()->route('transaksidetail.detail', ['id_transaksi' => $transaksi->id])
                             ->with('pesan', 'Berhasil mengubah data');
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();
            return redirect()->back()->withErrors(['Transaction' => 'Gagal mengubah data'])->withInput();
        }
    }

    // Menghapus transaksi detail
    public function destroy($id)
    {
        // Mencari transaksi detail yang akan dihapus
        $transaksidetail = TransaksiDetail::findOrFail($id);

        // Mulai transaksi DB
        DB::beginTransaction();

        try {
            // Menghapus transaksi detail
            $transaksidetail->delete();

            // Update total harga di transaksi
            $transaksi = Transaksi::findOrFail($transaksidetail->id_transaksi);
            $transaksi->total_harga = $transaksi->transaksidetail->sum('subtotal');
            $transaksi->kembalian = $transaksi->bayar - $transaksi->total_harga;
            $transaksi->save();

            // Commit transaksi DB
            DB::commit();

            // Redirect ke halaman detail transaksi
            return redirect()->route('transaksidetail.detail', ['id_transaksi' => $transaksi->id])
                             ->with('pesan', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();
            return redirect()->back()->withErrors(['Transaction' => 'Gagal menghapus data'])->withInput();
        }
    }
}

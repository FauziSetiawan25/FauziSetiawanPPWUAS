@extends('layouts')

@section('content')
    <h2>Tambah Transaksi</h2>
    <div class="card">
        <div class="card-header bg-white">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">Kembali</a>
        </div>
        <div class="card-body">
            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form tambah transaksi --}}
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="mb-4">
                    <div class="form-group">
                        <label for="tanggal_pembelian">Tanggal Pembelian</label>
                        <input type="date" id="tanggal_pembelian" name="tanggal_pembelian" class="form-control" value="{{ old('tanggal_pembelian') }}" required>
                    </div>
                </div>

                <h6>Produk yang Dibeli</h6>
                <div class="accordion mb-4" id="accordionItem">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $i > 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#item{{ $i }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="item{{ $i }}">
                                    Item #{{ $i }}
                                </button>
                            </h2>
                            <div id="item{{ $i }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" data-bs-parent="#accordionItem">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="nama_produk{{ $i }}">Nama Produk</label>
                                        <input type="text" id="nama_produk{{ $i }}" name="nama_produk{{ $i }}" class="form-control" value="{{ old('nama_produk' . $i) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga_satuan{{ $i }}">Harga Satuan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" id="harga_satuan{{ $i }}" name="harga_satuan{{ $i }}" class="form-control" value="{{ old('harga_satuan' . $i) }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah{{ $i }}">Jumlah</label>
                                        <input type="number" id="jumlah{{ $i }}" name="jumlah{{ $i }}" class="form-control" value="{{ old('jumlah' . $i) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subtotal{{ $i }}">Subtotal</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="subtotal{{ $i }}" name="subtotal{{ $i }}" class="form-control" value="{{ old('subtotal' . $i) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mb-3">
                    <label for="total_harga">Harga Total</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="total_harga" name="total_harga" class="form-control" value="{{ old('total_harga') }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="bayar">Bayar</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="bayar" name="bayar" class="form-control" value="{{ old('bayar') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kembalian">Kembalian</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="kembalian" name="kembalian" class="form-control" value="{{ old('kembalian') }}" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Custom JS --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const calculateSubtotal = (index) => {
                const hargaSatuan = parseInt(document.getElementById(`harga_satuan${index}`).value || 0);
                const jumlah = parseInt(document.getElementById(`jumlah${index}`).value || 0);
                const subtotal = hargaSatuan * jumlah;

                document.getElementById(`subtotal${index}`).value = subtotal;
                calculateTotal();
            };

            const calculateTotal = () => {
                let total = 0;
                for (let i = 1; i <= 3; i++) {
                    total += parseInt(document.getElementById(`subtotal${i}`).value || 0);
                }
                document.getElementById("total_harga").value = total;
                calculateKembalian();
            };

            const calculateKembalian = () => {
                const totalHarga = parseInt(document.getElementById("total_harga").value || 0);
                const bayar = parseInt(document.getElementById("bayar").value || 0);
                document.getElementById("kembalian").value = bayar - totalHarga;
            };

            for (let i = 1; i <= 3; i++) {
                document.getElementById(`harga_satuan${i}`).addEventListener("input", () => calculateSubtotal(i));
                document.getElementById(`jumlah${i}`).addEventListener("input", () => calculateSubtotal(i));
            }

            document.getElementById("bayar").addEventListener("input", calculateKembalian);
        });
    </script>
@endsection
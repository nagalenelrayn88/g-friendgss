@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold mb-4">Tambah Barang</h2>

    <form action="{{ auth()->user()->role == 'admin' 
    ? route('admin.barang.store') 
    : route('super.barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Harga Modal</label>
            <input type="text" name="harga_modal" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Harga Jual</label>
            <input type="text" name="harga" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Diskon</label>
            <select name="diskon_id" class="w-full border p-2 rounded">
                <option value="">-- Tidak Ada Diskon --</option>
                @foreach($diskon as $d)
                    <option value="{{ $d->id }}">
                        {{ $d->nama_diskon }} ({{ $d->persen }}%)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Gambar</label>
            <input type="file" name="gambar" class="w-full">
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Simpan
        </button>

    </form>

</div>

@endsection
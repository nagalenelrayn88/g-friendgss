@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold mb-4">Edit Barang</h2>

    <form action="{{ auth()->user()->role == 'admin' 
    ? route('admin.barang.update', $barang->id) 
    : route('super.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Harga Modal</label>
            <input type="text" name="harga_modal" value="{{ number_format($barang->harga_modal,0,',','.') }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Harga Jual</label>
            <input type="text" name="harga" value="{{ number_format($barang->harga,0,',','.') }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ $barang->stok }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label>Diskon</label>
            <select name="diskon_id" class="w-full border p-2 rounded">
                <option value="">-- Tidak Ada Diskon --</option>
                @foreach($diskon as $d)
                    <option value="{{ $d->id }}" 
                        {{ $barang->diskon_id == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_diskon }} ({{ $d->persen }}%)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Gambar</label><br>

            @if($barang->gambar)
                <img src="{{ asset('storage/'.$barang->gambar) }}" width="100" class="mb-2">
            @endif

            <input type="file" name="gambar" class="w-full">
        </div>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Update
        </button>

    </form>

</div>

@endsection
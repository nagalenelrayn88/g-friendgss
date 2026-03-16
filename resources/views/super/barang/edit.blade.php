@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')

<div class="flex items-center justify-center min-h-screen">

<div class="bg-gradient-to-br from-purple-600 to-pink-500 shadow-2xl rounded-2xl p-8 w-96 text-white">

<h2 class="text-2xl font-bold text-center mb-6">
Edit Barang
</h2>

<form method="POST" action="{{ route('super.barang.update',$barang->id) }}" enctype="multipart/form-data">

@csrf
@method('PUT')

<div class="mb-4">
<label>Nama Barang</label>

<input 
type="text" 
name="nama_barang"
maxlength="100"
required
value="{{ old('nama_barang',$barang->nama_barang) }}"
class="w-full mt-1 p-2 rounded-lg text-black">
</div>

<div class="mb-4">
<label>Harga Modal</label>

<input 
type="text"
name="harga_modal"
onkeyup="formatRupiah(this)"
value="{{ old('harga_modal',number_format($barang->harga_modal,0,',','.')) }}"
class="w-full mt-1 p-2 rounded-lg text-black">
</div>

<div class="mb-4">
<label>Harga</label>

<input 
type="text"
name="harga"
onkeyup="formatRupiah(this)"
value="{{ old('harga',number_format($barang->harga,0,',','.')) }}"
class="w-full mt-1 p-2 rounded-lg text-black">
</div>

<div class="mb-4">
<label>Stok</label>

<input 
type="number"
name="stok"
min="0"
max="10000"
value="{{ old('stok',$barang->stok) }}"
class="w-full mt-1 p-2 rounded-lg text-black">
</div>

{{-- FOTO PRODUK --}}
<div class="mb-4">

<label>Foto Produk</label>

<input
type="file"
name="gambar"
accept="image/*"
onchange="previewGambar(event)"
class="w-full mt-1 p-2 rounded-lg text-black bg-white">

{{-- FOTO LAMA --}}
@if($barang->gambar)

<img
src="{{ asset('storage/'.$barang->gambar) }}"
class="mt-3 rounded-lg w-32 h-32 object-cover">

@endif

{{-- PREVIEW FOTO BARU --}}
<img id="preview"
class="mt-3 rounded-lg hidden w-32 h-32 object-cover">

</div>


<div class="mb-6">
<label>Diskon</label>

<select name="diskon_id"
class="w-full mt-1 p-2 rounded-lg text-black">

<option value="">Tanpa Diskon</option>

@foreach($diskon as $d)

<option value="{{ $d->id }}"
{{ $barang->diskon_id == $d->id ? 'selected' : '' }}>

{{ $d->nama_diskon }} ({{ $d->persen }}%)

</option>

@endforeach

</select>

</div>

<button
class="w-full bg-white text-purple-600 font-semibold py-2 rounded-lg hover:scale-105 transition">
Update
</button>

</form>

</div>

</div>


<script>

function previewGambar(event){

let preview = document.getElementById('preview')

preview.src = URL.createObjectURL(event.target.files[0])

preview.classList.remove('hidden')

}


function formatRupiah(input){

let value = input.value.replace(/[^,\d]/g,'')

let number_string = value.toString()

let sisa = number_string.length % 3

let rupiah = number_string.substr(0, sisa)

let ribuan = number_string.substr(sisa).match(/\d{3}/gi)

if(ribuan){

let separator = sisa ? '.' : ''

rupiah += separator + ribuan.join('.')

}

input.value = rupiah

}

</script>

@endsection
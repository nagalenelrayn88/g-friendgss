@extends('layouts.app')

@section('title', 'Edit Diskon')

@section('content')

<div class="flex items-center justify-center min-h-screen">

<div class="bg-gradient-to-br from-purple-600 to-pink-500 shadow-2xl rounded-2xl p-8 w-96 text-white">

<h2 class="text-2xl font-bold text-center mb-6">
Edit Diskon
</h2>

<form method="POST" action="{{ route('super.diskon.update',$diskon->id) }}">

@csrf
@method('PUT')

<div class="mb-4">
<label>Nama Diskon</label>

<input 
type="text"
name="nama_diskon"
maxlength="100"
required
value="{{ old('nama_diskon',$diskon->nama_diskon) }}"
class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">

</div>

<div class="mb-6">
<label>Persentase Diskon (%)</label>

<input 
type="number"
name="persen"
min="0"
max="100"
required
value="{{ old('persen',$diskon->persen) }}"
class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">

<p class="text-xs mt-1 text-white/80">
Masukkan angka 0 - 100
</p>

</div>

<button
class="w-full bg-white text-purple-600 font-semibold py-2 rounded-lg hover:scale-105 transition">
Update
</button>

</form>

</div>

</div>

@endsection
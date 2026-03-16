@extends('layouts.app')

@section('title','Dashboard Admin')

@section('content')

<div class="grid grid-cols-3 gap-6 mb-6">

    <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Total Barang</h3>
        <p class="text-2xl font-bold mt-2">
            {{ $totalBarang ?? 0 }}
        </p>
    </div>

    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Total Diskon</h3>
        <p class="text-2xl font-bold mt-2">
            {{ $totalDiskon ?? 0 }}
        </p>
    </div>

    <div class="bg-gradient-to-r from-pink-500 to-rose-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Stok Menipis</h3>
        <p class="text-2xl font-bold mt-2">
            {{ $stokMenipis ?? 0 }}
        </p>
    </div>

</div>


<div class="grid grid-cols-3 gap-6">

    {{-- GRAFIK BARANG --}}
    <div class="col-span-2 bg-white p-6 rounded-xl shadow-lg">

        <h3 class="font-bold text-lg mb-4">
            Grafik Perubahan Barang
        </h3>

        <canvas id="grafikBarang"></canvas>

    </div>


    {{-- BARANG TERMAHAL & TERMURAH --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">

        <h3 class="font-bold text-lg mb-4">
            Informasi Barang
        </h3>

        <div class="mb-4">

            <p class="text-gray-500 text-sm">Barang Termahal</p>

            <p class="font-bold text-purple-700">
                {{ $barangTermahal->nama_barang ?? '-' }}
            </p>

            <p class="text-sm text-gray-600">
                Rp {{ number_format($barangTermahal->harga ?? 0,0,',','.') }}
            </p>

        </div>


        <div>

            <p class="text-gray-500 text-sm">Barang Termurah</p>

            <p class="font-bold text-pink-600">
                {{ $barangTermurah->nama_barang ?? '-' }}
            </p>

            <p class="text-sm text-gray-600">
                Rp {{ number_format($barangTermurah->harga ?? 0,0,',','.') }}
            </p>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('grafikBarang');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Perubahan Barang',
            data: [0,0,0,0,0,0,0,0,0,0,0,0],
            borderColor: '#9333ea',
            backgroundColor: 'rgba(147,51,234,0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        scales: {
            y: {
                min: 0,
                max: 450
            }
        }
    }
});

</script>

@endsection
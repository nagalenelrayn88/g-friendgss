@extends('layouts.app')

@section('title','Dashboard Super Admin')

@section('content')

<div class="grid grid-cols-4 gap-6 mb-6">

    <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Total Penjualan</h3>
        <p class="text-2xl font-bold mt-2">
            Rp {{ number_format($totalPenjualan ?? 0,0,',','.') }}
        </p>
    </div>

    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Total Transaksi</h3>
        <p class="text-2xl font-bold mt-2">
            {{ $totalTransaksi ?? 0 }}
        </p>
    </div>

    <div class="bg-gradient-to-r from-pink-500 to-rose-500 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Total Profit</h3>
        <p class="text-2xl font-bold mt-2">
            Rp {{ number_format($totalProfit ?? 0,0,',','.') }}
        </p>
    </div>

    <div class="bg-gradient-to-r from-purple-700 to-indigo-600 text-white p-6 rounded-xl shadow-lg">
        <h3 class="text-sm">Stok Menipis</h3>
        <p class="text-2xl font-bold mt-2">
            {{ $stokMenipis ?? 0 }}
        </p>
    </div>

</div>

<!-- Grafik -->
<div class="bg-white p-6 rounded-xl shadow-lg">
    <h3 class="mb-4 font-bold text-lg">Analytics Penjualan (Bulanan)</h3>
    <canvas id="salesChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// 🔥 LABEL BULAN
let labels = {!! json_encode(
    $grafikBulanan->pluck('bulan')->map(function($b){
        return \Carbon\Carbon::create()->month($b)->format('M');
    })
) !!};

// 🔥 DATA QTY
let data = {!! json_encode($grafikBulanan->pluck('total')) !!};

const ctx = document.getElementById('salesChart');

let hue = 0;

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Barang Terjual',
            data: data,
            borderColor: 'rgb(255,0,150)',
            backgroundColor: 'rgba(255,0,150,0.1)',
            tension: 0.4,
            borderWidth: 3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins:{
            legend:{
                labels:{
                    color:'#6b21a8'
                }
            }
        },
        scales:{
            x:{
                ticks:{ color:'#9333ea' }
            },
            y:{
                beginAtZero: true,
                ticks:{ color:'#9333ea' }
            }
        }
    }
});

// 🔥 ANIMASI WARNA
setInterval(() => {
    hue += 5;
    if(hue >= 360) hue = 0;
    chart.data.datasets[0].borderColor = `hsl(${hue}, 100%, 50%)`;
    chart.update();
}, 120);

</script>

@endsection
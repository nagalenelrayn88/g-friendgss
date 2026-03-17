@extends('layouts.app')

@section('title','Detail Transaksi')

@section('content')

<h2 class="text-2xl font-bold mb-4 text-purple-700">Detail Transaksi</h2>

<div class="bg-white p-5 rounded-xl shadow mb-6">

    <p><strong>Kode:</strong> {{ $trx->kode_transaksi }}</p>
    <p><strong>Metode:</strong> {{ $trx->metode }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y') }}</p>
    <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}</p>

</div>

<table class="w-full bg-white rounded-xl shadow overflow-hidden">

<thead class="bg-purple-600 text-white">
<tr>
    <th class="p-3 text-left">Gambar</th>
    <th class="p-3 text-left">Barang</th>
    <th class="p-3 text-center">Qty</th>
    <th class="p-3 text-right">Harga</th>
    <th class="p-3 text-right">Subtotal</th>
</tr>
</thead>

<tbody>

@foreach($detail as $item)
<tr class="border-b">

    <td class="p-3">
        @if($item->gambar)
            <img src="{{ asset('storage/'.$item->gambar) }}" class="w-14 h-14 object-cover rounded-lg">
        @else
            <div class="w-14 h-14 bg-gray-200 flex items-center justify-center rounded-lg text-xs text-gray-500">
                No Img
            </div>
        @endif
    </td>

    <td class="p-3">{{ $item->nama_barang }}</td>

    <td class="p-3 text-center">{{ $item->qty }}</td>

    <td class="p-3 text-right">
        Rp {{ number_format($item->harga,0,',','.') }}
    </td>

    <td class="p-3 text-right font-semibold text-purple-700">
        Rp {{ number_format($item->subtotal,0,',','.') }}
    </td>

</tr>
@endforeach

</tbody>

</table>

<!-- TOTAL -->
<div class="mt-6 bg-white p-5 rounded-xl shadow text-right">

    <p class="text-lg">
        <strong>Total:</strong>
        <span class="text-2xl font-bold text-purple-700">
            Rp {{ number_format($trx->total_harga,0,',','.') }}
        </span>
    </p>

</div>

@endsection
@extends('layouts.app')

@section('title','Riwayat Transaksi')

@section('content')

<div class="p-6">

<h2 class="text-2xl font-bold mb-6 text-purple-700">
Riwayat Transaksi
</h2>

<table class="w-full bg-white rounded-xl shadow overflow-hidden">

<thead class="bg-purple-600 text-white">
<tr>
<th class="p-3">Kode</th>
<th>Total</th>
<th>Metode</th>
<th>Tanggal</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

@foreach($transaksi as $trx)
<tr class="border-b hover:bg-gray-50">

<td class="p-3">{{ $trx->kode_transaksi }}</td>

<td class="p-3">
Rp {{ number_format($trx->total_harga,0,',','.') }}
</td>

<td class="p-3 capitalize">{{ $trx->metode }}</td>

<td class="p-3">
{{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y H:i') }}
</td>

<td class="p-3 space-x-2">

<a href="{{ route('operator.transaksi.show',$trx->id) }}"
class="bg-blue-500 text-white px-3 py-1 rounded">
Detail
</a>

<a href="{{ route('operator.struk.print',$trx->id) }}"
class="bg-green-500 text-white px-3 py-1 rounded">
Struk
</a>

</td>

</tr>
@endforeach

</tbody>

</table>

<div class="mt-4">
{{ $transaksi->links() }}
</div>

</div>

@endsection
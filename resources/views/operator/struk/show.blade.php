<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Struk {{ $transaksi->kode_transaksi }}</title>

<style>

body{
font-family: monospace;
width: 300px;
margin:auto;
}

.center{
text-align:center;
}

hr{
border:0;
border-top:1px dashed black;
margin:8px 0;
}

table{
width:100%;
font-size:12px;
}

td{
padding:2px 0;
}

.right{
text-align:right;
}

</style>

</head>

<body onload="window.print()">

<div class="center">

<img src="{{ public_path('images/logo-gfriend.png') }}" width="70">

<h3>G-FRIEND STORE</h3>

<p style="font-size:11px">
Terima kasih telah berbelanja
</p>

</div>

<hr>

<p style="font-size:12px">

Kode : {{ $transaksi->kode_transaksi }} <br>

Kasir : {{ $transaksi->user->name }} <br>

Tanggal : {{ $transaksi->created_at->format('d-m-Y') }} <br>

Jam : {{ $transaksi->created_at->format('H:i') }} <br>

Metode : {{ strtoupper($transaksi->metode_pembayaran) }}

</p>

<hr>

<table>

@foreach($transaksi->detail as $d)

<tr>
<td colspan="3">
{{ $d->barang->nama_barang }}
</td>
</tr>

<tr>

<td>
{{ $d->qty }} x {{ number_format($d->harga,0,',','.') }}
</td>

<td></td>

<td class="right">
{{ number_format($d->subtotal,0,',','.') }}
</td>

</tr>

@endforeach

</table>

<hr>

<table>

<tr>

<td><b>TOTAL</b></td>

<td class="right">
<b>
Rp {{ number_format($transaksi->total,0,',','.') }}
</b>
</td>

</tr>

</table>

<hr>

<div class="center" style="font-size:11px">

Barang yang sudah dibeli<br>
tidak dapat ditukar kembali

<br><br>

G-FRIEND POS SYSTEM

</div>

</body>
</html>
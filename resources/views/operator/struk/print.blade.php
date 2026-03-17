<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk G-Friend</title>
    <style>
        @page { size: 80mm auto; margin: 0; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #000;
            width: 80mm;
            margin: 0 auto;
            padding: 5px;
        }
        .header { text-align: center; margin-bottom: 6px; }
        .header img { width: 90px; margin-bottom: 3px; }
        .header small { font-size: 11px; line-height: 1.3; }

        .info { display: flex; justify-content: space-between; font-size: 12px; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }

        table { width: 100%; font-size: 12px; }
        td { padding: 3px 0; }

        .thanks {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>

<body onload="window.print()">

    <!-- 🔥 LOGO -->
    <div class="header">
        <img src="{{ asset('1773499502402-removebg-preview.png') }}"> {{-- TARO LOGO DI public/logo.png --}}
        <small>
            G-Friend Store<br>
            Jl. Sukamanah Cibogo<br>
            Kab. Bandung<br>
            Telp: 08xxxxxxx
        </small>
    </div>

    <div class="line"></div>

    <!-- INFO -->
    <div class="info">
        <span>{{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y H:i') }}</span>
        <span>#{{ $trx->id }}</span>
    </div>

    <div class="info">
        <span>Kasir: {{ auth()->user()->name }}</span>
    </div>

    <div class="line"></div>

    <!-- ITEM -->
    <table>
        @foreach ($detail as $i => $d)
        <tr>
            <td>
                {{ $i+1 }}. {{ $d->nama_barang }}
            </td>
            <td style="text-align:right;">
                {{ $d->qty }} x Rp{{ number_format($d->harga,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <!-- TOTAL -->
    <table>
        <tr>
            <td>Total</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->total_harga,0,',','.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- METODE -->
    <table>
        <tr>
            <td>Metode</td>
            <td style="text-align:right;">{{ ucfirst($trx->metode) }}</td>
        </tr>

        @if($trx->metode == 'cash')
        <tr>
            <td>Dibayar</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->uang_diterima,0,',','.') }}
            </td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->kembalian,0,',','.') }}
            </td>
        </tr>
        @endif
    </table>

    <div class="thanks">
        Terimakasih sudah berbelanja 💜
    </div>

</body>
</html>
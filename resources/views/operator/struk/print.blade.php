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
            width: 70mm; /* Dipersempit sedikit biar margin aman */
            margin: 0 auto;
            padding: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px; 
            display: flex;
            flex-direction: column;
            align-items: center; /* Logo ke tengah */
        }
        .header img { 
            width: 120px; /* Ukuran logo diperbesar */
            height: auto;
            margin-bottom: 8px; 
        }
        .header small { font-size: 11px; line-height: 1.3; }

        .info { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 2px; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }

        table { width: 100%; font-size: 12px; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        
        .discount-text {
            font-size: 10px;
            font-style: italic;
        }

        .thanks {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 8px;
        }
        .total-section td { font-weight: bold; font-size: 14px; }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <img src="{{ asset('1773499502402-removebg-preview.png') }}">
        <small>
            <strong>G-FRIEND STORE</strong><br>
            Jl. Sukamanah Cibogo, Kab. Bandung<br>
            Telp: 08xxxxxxx
        </small>
    </div>

    <div class="line"></div>

    <div class="info">
        <span>Tgl : {{ \Carbon\Carbon::parse($trx->created_at)->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info">
        <span>No  : #{{ $trx->kode_transaksi }}</span>
    </div>
    <div class="info">
        <span>Ksr : {{ auth()->user()->name }}</span>
    </div>

    <div class="line"></div>

    <table>
        @foreach ($detail as $d)
        <tr>
            <td colspan="2">{{ $d->nama_barang }}</td>
        </tr>
        <tr>
            <td>{{ $d->qty }} x {{ number_format($d->harga_asli,0,',','.') }}</td>
            <td style="text-align:right;">{{ number_format($d->qty * $d->harga_asli,0,',','.') }}</td>
        </tr>
        
        {{-- Logika Menampilkan Diskon per Item --}}
        @if($d->persen > 0)
        <tr class="discount-text">
            <td style="padding-left: 10px;">↳ Diskon: {{ $d->nama_diskon }} ({{ $d->persen }}%)</td>
            <td style="text-align:right;">-{{ number_format(($d->harga_asli * $d->persen / 100) * $d->qty, 0, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach
    </table>

    <div class="line"></div>

    <table class="total-section">
        <tr>
            <td>TOTAL</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->total_harga,0,',','.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Metode</td>
            <td style="text-align:right;">{{ strtoupper($trx->metode) }}</td>
        </tr>

        {{-- Pastikan nama kolom ini sesuai dengan migration kamu (uang_bayar / uang_diterima) --}}
        @if($trx->metode == 'cash')
        <tr>
            <td>Bayar</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->total_harga + ($trx->kembalian ?? 0), 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td style="text-align:right;">
                Rp{{ number_format($trx->kembalian ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        @endif
    </table>

    <div class="thanks">
        ***********************************<br>
        TERIMA KASIH ATAS KUNJUNGAN ANDA<br>
        BARANG YANG SUDAH DIBELI<br>
        TIDAK DAPAT DITUKAR/DIKEMBALIKAN<br>
        ***********************************
    </div>

</body>
</html>
<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LAPORAN TRANSAKSI
    |--------------------------------------------------------------------------
    */
    public function transaksi(Request $request)
    {
        $query = Transaksi::query();

        // FILTER TANGGAL
        if ($request->dari && $request->sampai) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $transaksi = $query->latest()->get();

        // ✅ PAKAI total
        $totalPendapatan = $transaksi->sum('total');
        $totalTransaksi = $transaksi->count();

        return view('super.laporan.transaksi', compact(
            'transaksi',
            'totalPendapatan',
            'totalTransaksi'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENDAPATAN (REKAP HARIAN)
    |--------------------------------------------------------------------------
    */
    public function pendapatan(Request $request)
    {
        $query = Transaksi::query();

        // FILTER TANGGAL
        if ($request->dari && $request->sampai) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        // ✅ REKAP PER HARI (PAKAI total)
        $pendapatan = $query
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalKeseluruhan = $pendapatan->sum('total');

        return view('super.laporan.pendapatan', compact(
            'pendapatan',
            'totalKeseluruhan'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN STOK BARANG
    |--------------------------------------------------------------------------
    */
    public function stok()
    {
        $barang = Barang::orderBy('stok', 'asc')->get();

        $stokMenipis = Barang::where('stok', '<=', 5)->count();

        return view('super.laporan.stok', compact(
            'barang',
            'stokMenipis'
        ));
    }
}
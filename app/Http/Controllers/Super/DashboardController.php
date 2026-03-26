<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenjualan = DB::table('transaksi')->sum('total_harga');

        $totalTransaksi = DB::table('transaksi')->count();

        $totalProfit = DB::table('detail_transaksi')
            ->join('barang','detail_transaksi.barang_id','=','barang.id')
            ->select(DB::raw('SUM((detail_transaksi.harga - barang.harga_modal) * detail_transaksi.qty) as profit'))
            ->value('profit');

        $stokMenipis = DB::table('barang')
            ->where('stok','<=',5)
            ->count();

        // 🔥 GRAFIK BULANAN (JUMLAH TRANSAKSI)
        $grafikBulanan = DB::table('transaksi')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(id) as total') // Pakai COUNT, bukan SUM QTY
            ->whereYear('created_at', date('Y')) // Tambahin biar cuma tahun ini aja
            ->groupBy('bulan')
            ->orderBy('bulan','asc')
            ->get();
        return view('super.dashboard', compact(
            'totalPenjualan',
            'totalTransaksi',
            'totalProfit',
            'stokMenipis',
            'grafikBulanan'
        ));
    }
}
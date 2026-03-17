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

        // 🔥 GRAFIK BULANAN (QTY TERJUAL)
        $grafikBulanan = DB::table('detail_transaksi')
            ->join('transaksi','detail_transaksi.transaksi_id','=','transaksi.id')
            ->selectRaw('MONTH(transaksi.created_at) as bulan, SUM(detail_transaksi.qty) as total')
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
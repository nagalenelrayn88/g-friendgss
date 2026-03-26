<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $totalBarang = DB::table('barang')->count();
    $totalDiskon = DB::table('diskon')->count();
    $stokMenipis = DB::table('barang')->where('stok','<=',5)->count();
    $barangTermahal = DB::table('barang')->orderByDesc('harga')->first();
    $barangTermurah = DB::table('barang')->orderBy('harga')->first();

    // 🔥 AMBIL DATA PENAMBAHAN BARANG PER BULAN
    $perubahanBarang = DB::table('barang')
        ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get()
        ->pluck('total', 'bulan') // Hasilnya: [bulan => total]
        ->toArray();

    // Pastikan semua bulan (1-12) ada isinya, kalau kosong kasih 0
    $dataGrafik = [];
    for ($i = 1; $i <= 12; $i++) {
        $dataGrafik[] = $perubahanBarang[$i] ?? 0;
    }

    return view('admin.dashboard', compact(
        'totalBarang',
        'totalDiskon',
        'stokMenipis',
        'barangTermahal',
        'barangTermurah',
        'dataGrafik' // <-- Kirim ini ke view
    ));
}
}
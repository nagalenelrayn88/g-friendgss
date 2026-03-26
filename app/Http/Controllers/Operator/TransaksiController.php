<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{

    public function index()
    {
        $transaksi = DB::table('transaksi')
            ->orderBy('created_at','desc')
            ->get();

        return view('operator.transaksi.index', compact('transaksi'));
    }


   public function create()
{
    $barang = DB::table('barang')
        ->leftJoin('diskon', 'barang.diskon_id', '=', 'diskon.id')
        ->where('barang.stok', '>', 0)
        ->select(
            'barang.*', 
            'diskon.persen as diskon_persen', 
            'diskon.nama_diskon as nama_diskon_db' // Kita beri alias agar unik
        )
        ->get();

    return view('operator.transaksi.create', compact('barang'));
}


  public function store(Request $request)
{
    $request->validate([
        'barang_id' => 'required|array',
        'qty' => 'required|array',
        'metode' => 'required',
        'bayar' => 'required_if:metode,cash' // Validasi uang bayar jika cash
    ]);

    DB::beginTransaction();

    try {
        $kode = 'GF-' . date('YmdHis');

        // 🔥 AMBIL NILAI UANG DITERIMA DARI FORM
        $uangDiterima = $request->metode == 'cash' ? $request->bayar : 0;

        // 1. Simpan Header Transaksi dulu
        $transaksiId = DB::table('transaksi')->insertGetId([
            'kode_transaksi' => $kode,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'metode' => $request->metode,
            'total' => 0,
            'total_harga' => 0,
            'uang_diterima' => $uangDiterima, // Simpan uang yang dikasih pelanggan
            'kembalian' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $totalFinal = 0;

        // 2. Looping barang yang dibeli
        foreach ($request->barang_id as $index => $barangId) {
            
            // 🔥 DISINI KUNCINYA: Ambil barang + Join ke tabel diskon
            $barang = DB::table('barang')
                ->leftJoin('diskon', 'barang.diskon_id', '=', 'diskon.id')
                ->where('barang.id', $barangId)
                ->select('barang.*', 'diskon.persen as diskon_persen') // ambil persen diskonnya
                ->first();

            if(!$barang){
                throw new \Exception('Barang tidak ditemukan');
            }

            $qty = $request->qty[$index];

            if($barang->stok < $qty){
                throw new \Exception('Stok barang ' . $barang->nama_barang . ' tidak cukup');
            }

            // --- LOGIKA HITUNG DISKON OTOMATIS ---
            $hargaAsli = $barang->harga;
            $potongan = 0;

            // Kalau di tabel barang ada diskon_id dan di tabel diskon ada persennya
            if ($barang->diskon_persen > 0) {
                $potongan = ($barang->diskon_persen / 100) * $hargaAsli;
            }

            $hargaSetelahDiskon = $hargaAsli - $potongan;
            $subtotal = $hargaSetelahDiskon * $qty;
            // -------------------------------------

            // 3. Simpan ke Detail Transaksi (pakai harga yang sudah dipotong diskon)
            DB::table('detail_transaksi')->insert([
                'transaksi_id' => $transaksiId,
                'barang_id' => $barangId,
                'qty' => $qty,
                'harga' => $hargaSetelahDiskon, 
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4. Kurangi Stok
            DB::table('barang')
                ->where('id', $barangId)
                ->decrement('stok', $qty);

            $totalFinal += $subtotal;
        }

        // 🔥 HITUNG KEMBALIAN NYATA
        $kembalian = $request->metode == 'cash' ? ($uangDiterima - $totalFinal) : 0;
        if($kembalian < 0) $kembalian = 0;

        // 5. Update Total & Kembalian di tabel transaksi utama
        DB::table('transaksi')
            ->where('id', $transaksiId)
            ->update([
                'total' => $totalFinal,
                'total_harga' => $totalFinal,
                'kembalian' => $kembalian // 🔥 Update kembalian yang benar di sini
            ]);

        DB::commit();

        return redirect()->route('operator.struk.print', $transaksiId)
                         ->with('success', 'Transaksi Berhasil!');

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}


    public function show($id)
{
    $trx = DB::table('transaksi')->where('id', $id)->first();

    // 🔥 HANDLE BIAR GA ERROR NULL
    if (!$trx) {
        abort(404, 'Transaksi tidak ditemukan');
    }

    $detail = DB::table('detail_transaksi')
        ->join('barang','detail_transaksi.barang_id','=','barang.id')
        ->select(
            'barang.nama_barang',
            'barang.gambar',
            'detail_transaksi.qty',
            'detail_transaksi.harga',
            'detail_transaksi.subtotal'
        )
        ->where('transaksi_id', $id)
        ->get();

    return view('operator.transaksi.show', compact('trx','detail'));
}

  public function print($id)
{
    $trx = DB::table('transaksi')->where('id', $id)->first();

    $detail = DB::table('detail_transaksi')
        ->join('barang', 'barang.id', '=', 'detail_transaksi.barang_id')
        ->leftJoin('diskon', 'barang.diskon_id', '=', 'diskon.id') // Join ke diskon
        ->where('transaksi_id', $id)
        ->select(
            'barang.nama_barang',
            'barang.harga as harga_asli', // Harga sebelum diskon
            'detail_transaksi.qty',
            'detail_transaksi.harga', // Harga setelah diskon
            'detail_transaksi.subtotal',
            'diskon.persen',
            'diskon.nama_diskon'
        )
        ->get();

    return view('operator.struk.print', compact('trx', 'detail'));
}

    public function riwayat()
    {
        $transaksi = DB::table('transaksi')
            ->orderBy('created_at','desc')
            ->paginate(10);

        return view('operator.riwayat-transaksi.index',compact('transaksi'));
    }

}
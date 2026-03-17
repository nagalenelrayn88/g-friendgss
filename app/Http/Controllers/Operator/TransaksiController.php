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
            ->where('stok','>',0)
            ->get();

        return view('operator.transaksi.create', compact('barang'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array',
            'qty' => 'required|array',
            'metode' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $kode = 'GF-' . date('YmdHis');

            // 🔥 SIMPAN TRANSAKSI
            $transaksiId = DB::table('transaksi')->insertGetId([
                'kode_transaksi' => $kode,
                'user_id' => \Illuminate\Support\Facades\Auth::id(), // 🔥 FIX ERROR
                'metode' => $request->metode,
                'total' => 0,
                'total_harga' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $total = 0;

            foreach ($request->barang_id as $index => $barangId) {

                $barang = DB::table('barang')
                    ->where('id',$barangId)
                    ->first();

                if(!$barang){
                    throw new \Exception('Barang tidak ditemukan');
                }

                $qty = $request->qty[$index];

                if($barang->stok < $qty){
                    throw new \Exception('Stok tidak cukup');
                }

                $subtotal = $barang->harga * $qty;

                DB::table('detail_transaksi')->insert([
                    'transaksi_id' => $transaksiId,
                    'barang_id' => $barangId,
                    'qty' => $qty,
                    'harga' => $barang->harga,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('barang')
                    ->where('id',$barangId)
                    ->decrement('stok', $qty);

                $total += $subtotal;
            }

            // 🔥 UPDATE TOTAL
            DB::table('transaksi')
                ->where('id',$transaksiId)
                ->update([
                    'total' => $total,
                    'total_harga' => $total
                ]);

            DB::commit();

            // 🔥 REDIRECT KE STRUK
            return redirect()->route('operator.struk.print', $transaksiId);

        } catch (\Exception $e) {

            DB::rollback();

            // 🔥 DEBUG (kalau masih error)
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
        $trx = DB::table('transaksi')
            ->where('id',$id)
            ->first();

        $detail = DB::table('detail_transaksi')
            ->join('barang','barang.id','=','detail_transaksi.barang_id')
            ->where('transaksi_id',$id)
            ->select(
                'barang.nama_barang',
                'detail_transaksi.qty',
                'detail_transaksi.harga',
                'detail_transaksi.subtotal'
            )
            ->get();

        return view('operator.struk.print',compact('trx','detail'));
    }


    public function riwayat()
    {
        $transaksi = DB::table('transaksi')
            ->orderBy('created_at','desc')
            ->paginate(10);

        return view('operator.riwayat-transaksi.index',compact('transaksi'));
    }

}
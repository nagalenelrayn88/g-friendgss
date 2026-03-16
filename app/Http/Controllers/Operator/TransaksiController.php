<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LIST TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $transaksi = DB::table('transaksi')
            ->orderBy('created_at','desc')
            ->get();

        return view('operator.transaksi.index', compact('transaksi'));
    }



    /*
    |--------------------------------------------------------------------------
    | HALAMAN KASIR
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        $barang = DB::table('barang')
            ->where('stok','>',0)
            ->get();

        return view('operator.transaksi.create', compact('barang'));

    }



    /*
    |--------------------------------------------------------------------------
    | SIMPAN TRANSAKSI
    |--------------------------------------------------------------------------
    */

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

            $transaksiId = DB::table('transaksi')->insertGetId([

                'kode_transaksi' => $kode,
                'metode' => $request->metode,
                'total' => $request->total,
                'created_at' => now(),
                'updated_at' => now()

            ]);



            foreach ($request->barang_id as $index => $barangId) {

                $barang = DB::table('barang')
                    ->where('id',$barangId)
                    ->first();

                $qty = $request->qty[$index];

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
                    ->decrement('stok',$qty);

            }


            DB::commit();


            return redirect()->route('operator.cetak-struk.show',$transaksiId);


        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error','Transaksi gagal disimpan');

        }

    }



    /*
    |--------------------------------------------------------------------------
    | DETAIL TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function show($id)
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


        return view('operator.transaksi.show',compact('trx','detail'));

    }



    /*
    |--------------------------------------------------------------------------
    | RIWAYAT TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function riwayat()
    {

        $transaksi = DB::table('transaksi')
            ->orderBy('created_at','desc')
            ->paginate(10);

        return view('operator.riwayat-transaksi.index',compact('transaksi'));

    }



    /*
    |--------------------------------------------------------------------------
    | STRUK
    |--------------------------------------------------------------------------
    */

    public function cetakShow($id)
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


        return view('operator.cetak-struk.show',compact('trx','detail'));

    }

}
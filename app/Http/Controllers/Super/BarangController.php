<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Barang;
use App\Models\Diskon;

class BarangController extends Controller
{

    public function index()
    {
        $barang = Barang::with('diskon')->latest()->get();

        return view('super.barang.index', compact('barang'));
    }

    public function create()
    {
        $diskon = Diskon::all();

        return view('super.barang.create', compact('diskon'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_barang' => 'required|max:100',
            'harga_modal' => 'required',
            'harga' => 'required',
            'stok' => 'required|integer|min:0|max:10000',
            'diskon_id' => 'nullable|exists:diskon,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // hapus titik rupiah
        $harga_modal = str_replace('.', '', $request->harga_modal);
        $harga = str_replace('.', '', $request->harga);

        $gambar = null;

        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar')->store('barang','public');
        }

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga_modal' => $harga_modal,
            'harga' => $harga,
            'stok' => $request->stok,
            'diskon_id' => $request->diskon_id,
            'gambar' => $gambar
        ]);

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.barang.index')
                ->with('success','Barang berhasil ditambahkan');
        }

        return redirect()->route('super.barang.index')
            ->with('success','Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $diskon = Diskon::all();

        return view('super.barang.edit', compact('barang','diskon'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nama_barang' => 'required|max:100',
            'harga_modal' => 'required',
            'harga' => 'required',
            'stok' => 'required|integer|min:0|max:10000',
            'diskon_id' => 'nullable|exists:diskon,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $harga_modal = str_replace('.', '', $request->harga_modal);
        $harga = str_replace('.', '', $request->harga);

        $barang = Barang::findOrFail($id);

        if($request->hasFile('gambar')){

            // hapus gambar lama
            if($barang->gambar){
                Storage::delete('public/'.$barang->gambar);
            }

            $gambar = $request->file('gambar')->store('barang','public');

            $barang->gambar = $gambar;
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->harga_modal = $harga_modal;
        $barang->harga = $harga;
        $barang->stok = $request->stok;
        $barang->diskon_id = $request->diskon_id;

        $barang->save();

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.barang.index')
                ->with('success','Barang berhasil diupdate');
        }

        return redirect()->route('super.barang.index')
            ->with('success','Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if($barang->gambar){
            Storage::delete('public/'.$barang->gambar);
        }

        $barang->delete();

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.barang.index')
                ->with('success','Barang berhasil dihapus');
        }

        return redirect()->route('super.barang.index')
            ->with('success','Barang berhasil dihapus');
    }
}
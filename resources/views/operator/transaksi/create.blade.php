@extends('layouts.app')

@section('title','Kasir')

@section('content')

<div class="flex h-screen overflow-hidden bg-gradient-to-br from-purple-100 via-pink-100 to-purple-200">

{{-- PRODUK --}}
<div class="flex-1 p-6 overflow-y-auto bg-white/40 backdrop-blur-sm">

<h2 class="text-2xl font-bold mb-4 text-purple-700">
Kasir
</h2>

<input
type="text"
id="searchProduk"
placeholder="Cari produk..."
class="w-full mb-6 border rounded-lg p-2 focus:ring-2 focus:ring-purple-400"
onkeyup="searchProduk()"
/>

<div id="produkGrid" class="grid grid-cols-4 gap-5">

@foreach($barang as $b)

<div 
class="produk-card bg-white rounded-xl shadow p-3 text-center
transition hover:shadow-xl hover:-translate-y-1
{{ $b->stok == 0 ? 'opacity-50' : '' }}"
data-nama="{{ strtolower($b->nama_barang) }}"
>

<img 
src="{{ $b->gambar ? asset('storage/'.$b->gambar) : 'https://via.placeholder.com/150?text=Produk' }}"
class="h-32 w-full object-cover rounded-lg mb-3"
>

<h3 class="font-semibold text-sm line-clamp-2 h-10">
{{ $b->nama_barang }}
</h3>

<p class="text-purple-600 font-bold mb-1">
Rp {{ number_format($b->harga,0,',','.') }}
</p>

@if($b->stok <= 5 && $b->stok > 0)
<p class="text-red-500 text-sm mb-2">
Stok tinggal {{ $b->stok }}
</p>
@else
<p class="text-gray-500 text-sm mb-2">
Stok {{ $b->stok }}
</p>
@endif

@if($b->stok > 0)
<button
    onclick="tambahProduk(
        {{ $b->id }}, 
        '{{ $b->nama_barang }}', 
        {{ $b->harga }}, 
        {{ $b->diskon_persen ?? 0 }}, 
        '{{ $b->nama_diskon_db ?? '' }}',
        {{ $b->stok }}
    )"
    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg">
    +
</button>
@else
<button class="bg-gray-400 text-white px-3 py-1 rounded-lg cursor-not-allowed">Habis</button>
@endif

</div>

@endforeach

</div>

</div>


{{-- PANEL PEMBAYARAN --}}
<div id="paymentPanel"
class="w-96 bg-gradient-to-b from-white to-purple-50 backdrop-blur-md border-l shadow-lg transform translate-x-full transition duration-300 flex flex-col">

<div class="p-4 border-b flex justify-between items-center">
<h3 class="font-bold text-lg">Pembayaran</h3>
<button type="button" onclick="togglePanel()">❮</button>
</div>

<form id="formTransaksi" action="{{ route('operator.transaksi.store') }}" method="POST" class="flex-1 flex flex-col">
@csrf

<input type="hidden" name="total" id="total_input">

<div class="flex-1 overflow-y-auto p-4">

<table class="w-full text-sm">
<thead>
<tr class="border-b">
<th>Produk</th>
<th>Qty</th>
<th>Subtotal</th>
<th></th>
</tr>
</thead>

<tbody id="cart"></tbody>

</table>

</div>

<div class="border-t p-4 space-y-3">

<div class="flex justify-between font-bold text-lg">
<span>Total</span>
<span>Rp <span id="total">0</span></span>
</div>

<select name="metode" id="metode" class="w-full border p-2 rounded" onchange="handleMetode()">
<option value="cash">Cash</option>
<option value="transfer">Transfer</option>
</select>

<input
    type="number"
    id="bayar"
    name="bayar" 
    placeholder="Uang bayar"
    class="w-full border p-2 rounded"
    oninput="hitungKembalian()"
/>

<div class="flex justify-between text-green-600 font-bold">
<span>Kembalian</span>
<span>Rp <span id="kembalian">0</span></span>
</div>

{{-- ✅ FIX DI SINI --}}
<button type="submit"
class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg">
Simpan Transaksi
</button>

</div>

</form>

</div>


{{-- TOMBOL PANEL --}}
<button
type="button"
onclick="togglePanel()"
class="fixed right-0 top-1/2 bg-purple-600 text-white px-3 py-2 rounded-l-lg">
❮
</button>

</div>


<script>
// Kita pakai window. agar fungsi bisa dipanggil oleh atribut onclick di HTML
window.cart = {};

// 1. Fungsi Tambah Produk
window.tambahProduk = function(id, nama, harga, diskon, namaDiskon, stok) {
    if (!window.cart[id]) {
        if (stok < 1) {
            Swal.fire({ 
                icon: 'error', 
                title: 'Waduh!', 
                text: 'Stok barang ini habis.', 
                confirmButtonColor: '#9333ea' 
            });
            return;
        }

        window.cart[id] = {
            nama: nama,
            hargaAsli: harga,
            diskon: diskon,
            namaDiskon: namaDiskon,
            qty: 1,
            stokMax: stok
        }
    } else {
        if (window.cart[id].qty + 1 > window.cart[id].stokMax) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Stok cuma ada ${window.cart[id].stokMax}, jangan maruk ya! 😂`,
                confirmButtonColor: '#9333ea'
            });
            return;
        }
        window.cart[id].qty++;
    }

    renderCart();
    document.getElementById("paymentPanel").classList.remove("translate-x-full");
}

// 2. Render Tampilan Keranjang
window.renderCart = function() {
    let tbody = document.getElementById("cart");
    tbody.innerHTML = "";
    let totalSemua = 0;

    for (let id in window.cart) {
        let item = window.cart[id];
        let subtotalNormal = item.hargaAsli * item.qty;
        let nominalPotongan = (item.hargaAsli * item.diskon / 100) * item.qty;
        let subtotalAkhir = subtotalNormal - nominalPotongan;
        totalSemua += subtotalAkhir;

        tbody.innerHTML += `
        <tr class="border-t">
            <td class="py-2 font-medium">
                ${item.nama}
                <input type="hidden" name="barang_id[]" value="${id}">
                <input type="hidden" name="qty[]" value="${item.qty}">
            </td>
            <td class="py-2 text-center">
                <input type="number" value="${item.qty}" min="1" max="${item.stokMax}"
                onchange="ubahQty(${id}, this.value)" 
                class="w-12 border rounded text-center text-xs p-1">
            </td>
            <td class="py-2 text-right">
                Rp ${subtotalNormal.toLocaleString('id-ID')}
            </td>
            <td class="py-2 text-center">
                <button type="button" onclick="hapusProduk(${id})" class="text-red-500 font-bold">✕</button>
            </td>
        </tr>
        `;

        if (item.diskon > 0) {
            tbody.innerHTML += `
            <tr class="text-[11px] text-red-500 italic bg-red-50">
                <td colspan="2" class="px-2 py-1 font-semibold">
                    ↳ ${item.namaDiskon} (${item.diskon}%)
                </td>
                <td class="px-2 py-1 text-right font-semibold">
                    - Rp ${nominalPotongan.toLocaleString('id-ID')}
                </td>
                <td></td>
            </tr>
            `;
        }
    }

    document.getElementById("total").innerText = totalSemua.toLocaleString('id-ID');
    document.getElementById("total_input").value = totalSemua;
    hitungKembalian();
}

// 3. Ubah Qty
window.ubahQty = function(id, val) {
    let inputVal = parseInt(val);
    if (inputVal > window.cart[id].stokMax) {
        Swal.fire('Stok Kurang', `Maksimal cuma bisa beli ${window.cart[id].stokMax}`, 'warning');
        window.cart[id].qty = window.cart[id].stokMax;
    } else if (inputVal < 1 || isNaN(inputVal)) {
        window.cart[id].qty = 1;
    } else {
        window.cart[id].qty = inputVal;
    }
    renderCart();
}

// 4. Hapus Produk
window.hapusProduk = function(id) {
    delete window.cart[id];
    renderCart();
}

// 5. Metode Pembayaran
window.handleMetode = function() {
    let metode = document.getElementById("metode").value;
    let bayar = document.getElementById("bayar");
    if (metode === "transfer") {
        bayar.value = ""; 
        bayar.disabled = true;
        document.getElementById("kembalian").innerText = "0";
    } else {
        bayar.disabled = false;
    }
}

// 6. Hitung Kembalian
window.hitungKembalian = function() {
    let total = parseInt(document.getElementById("total_input").value) || 0;
    let bayar = parseInt(document.getElementById("bayar").value) || 0;
    let kembali = bayar - total;
    if (kembali < 0) kembali = 0;
    document.getElementById("kembalian").innerText = kembali.toLocaleString('id-ID');
}

// 7. Cari Produk
window.searchProduk = function() {
    let keyword = document.getElementById("searchProduk").value.toLowerCase();
    let produk = document.querySelectorAll(".produk-card");
    produk.forEach(card => {
        let nama = card.dataset.nama;
        card.style.display = nama.includes(keyword) ? "block" : "none";
    });
}

// 8. Toggle Panel
window.togglePanel = function() {
    document.getElementById("paymentPanel").classList.toggle("translate-x-full");
}

// 9. Validasi Submit Form
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("formTransaksi");
    if (form) {
        form.addEventListener("submit", function(e) {
            if (Object.keys(window.cart).length === 0) {
                Swal.fire('Kosong!', 'Pilih barang dulu dong.', 'info');
                e.preventDefault(); 
                return;
            }

            let metode = document.getElementById("metode").value;
            let total = parseInt(document.getElementById("total_input").value) || 0;
            let bayar = parseInt(document.getElementById("bayar").value) || 0;
            
            if (metode === "cash" && bayar < total) {
                Swal.fire('Uang Kurang', 'Masa mau ngutang? Bayarnya kurang tuh!', 'error');
                e.preventDefault(); 
                return;
            }

            Swal.fire({
                title: 'Memproses...',
                text: 'Sabar ya, lagi nyimpen data.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });
        });
    }
});
</script>

@endsection
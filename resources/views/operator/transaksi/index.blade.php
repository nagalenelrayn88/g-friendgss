@extends('layouts.app')

@section('title','Transaksi')

@section('content')

<div class="min-h-screen p-8 bg-gradient-to-br from-white via-pink-100 to-pink-300">

<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-3xl font-bold text-purple-700">
            List Transaksi
        </h2>

        <a href="{{ route('operator.transaksi.create') }}"
        class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition">
            + Buat Transaksi
        </a>

    </div>


    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-gradient-to-r from-purple-600 to-pink-500 text-white">

                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Kode Transaksi</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($transaksi as $index => $t)

                    <tr class="border-b hover:bg-pink-50 transition">

                        <td class="p-4">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-4 font-semibold text-purple-700">
                            {{ $t->kode_transaksi }}
                        </td>

                        <td class="p-4">
                            Rp {{ number_format($t->total,0,',','.') }}
                        </td>

                        <td class="p-4 text-gray-600">
                            {{ $t->created_at }}
                        </td>

                        <td class="p-4 text-center space-x-2">

                            <a href="{{ route('operator.transaksi.show',$t->id) }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition">
                                Detail
                            </a>

                            <a href="{{ route('operator.struk.print',$t->id) }}"
                            class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">
                                Print
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            Belum ada transaksi
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
@extends('layouts.app')

@section('title', 'List User')

@section('content')

<div class="min-h-screen p-8 bg-gradient-to-br from-white via-pink-100 to-pink-300">

    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-purple-700">
                List User
            </h2>

            <a href="{{ route('super.create') }}"
               class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition">
                + Tambah User
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

            <table class="w-full text-left">
                <thead class="bg-gradient-to-r from-purple-600 to-pink-500 text-white">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Username</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $index => $user)
                        <tr class="border-b hover:bg-pink-50 transition">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4">{{ $user->name }}</td>
                            <td class="p-4">{{ $user->email }}</td>
                            <td class="p-4 capitalize">{{ $user->role }}</td>

                           <td class="p-4 text-center space-x-2">

    {{-- Tombol Edit --}}
    @if($user->email !== 'super@gfriend.com')
        <a href="{{ route('super.edit', $user->id) }}"
           class="bg-yellow-400 text-white px-3 py-1 rounded-lg hover:opacity-80 transition">
           Edit
        </a>
    @endif

    {{-- Tombol Hapus --}}
    @if($user->email !== 'super@gfriend.com' && auth()->id() !== $user->id)
        <form action="{{ route('super.destroy', $user->id) }}"
              method="POST"
              class="inline-block"
              onsubmit="return confirm('Yakin hapus user ini?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition">
                Hapus
            </button>

        </form>
    @endif

</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                Belum ada data user
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
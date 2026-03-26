@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="flex items-center justify-center min-h-screen">

    <div class="bg-gradient-to-br from-purple-600 to-pink-500 shadow-2xl rounded-2xl p-8 w-96 text-white">

        <h2 class="text-2xl font-bold text-center mb-6">
            Edit User
        </h2>

       <form method="POST" action="{{ route('super.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label>Username</label>
        <input type="text" name="name"
            value="{{ old('name', $user->name) }}" required
            class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">
    </div>

    <div class="mb-4">
        <label>Email</label>
        <input type="email" name="email"
            value="{{ old('email', $user->email) }}" required
            class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">
    </div>

    <div class="mb-4">
        <label>Password (Opsional)</label>
        <input type="password" name="password"
            placeholder="Kosongkan jika tidak diganti"
            class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">
    </div>

    <div class="mb-6">
        <label>Role</label>
        <select name="role" required
            class="w-full mt-1 p-2 rounded-lg text-black focus:outline-none">
            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
        </select>
    </div>

    <button type="submit"
        class="w-full bg-white text-purple-600 font-semibold py-2 rounded-lg hover:scale-105 transition">
        Update
    </button>
</form>

    </div>

</div>

@endsection
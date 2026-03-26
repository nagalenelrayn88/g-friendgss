<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login G-Friend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(270deg, #7c3aed, #a855f7, #ec4899);
            background-size: 600% 600%;
            animation: gradient 12s ease infinite;
        }

        @keyframes gradient {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        /* 🔥 TEXT GRADIENT */
        .text-gradient {
            background: linear-gradient(to right, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="bg-white/20 backdrop-blur-lg shadow-xl rounded-2xl p-8 w-[420px] border border-white/30">

        <!-- LOGO -->
        <div class="text-center mb-4">
            <img src="{{ asset('1773499502402-removebg-preview (1).png') }}"
                 class="mx-auto"
                 style="width: 350px;">
        </div>

        <!-- 🔥 TITLE -->
        <h2 class="text-3xl font-bold text-center mb-6 text-white">
            Login
        </h2>

        <form method="POST" action="/login">
            @csrf

            <div class="mb-4">
                <label class="text-white">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 p-2 rounded-lg bg-white/70 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="text-white">Password</label>
                <input type="password" name="password"
                    class="w-full mt-1 p-2 rounded-lg bg-white/70 focus:outline-none">
            </div>

            <button
                class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-2 rounded-lg hover:scale-105 transition">
                Login
            </button>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
        // 1. Tangkap Error Login (Email/Password Salah)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: '{{ $errors->first() }}', 
                confirmButtonColor: '#9333ea',
                background: '#ffffff',
                showClass: {
                    popup: 'animate__animated animate__shakeX' // Efek getar biar mantap
                }
            });
        @endif

        // 2. Tangkap Pesan Error Session Manual
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Waduh!',
                text: '{{ session("error") }}',
                confirmButtonColor: '#9333ea'
            });
        @endif
        
        // 3. Tambahkan efek loading pas tombol login diklik
        document.querySelector('form').addEventListener('submit', function() {
            let btn = this.querySelector('button');
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    </script>
</body>

</body>
</html>
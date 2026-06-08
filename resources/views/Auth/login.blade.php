<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Digital Banking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-red-700 mb-2">Selamat Datang</h1>
        <p class="text-gray-500 text-sm mb-6">Masuk ke akun Bank Untar Anda</p>
    
    <div class="flex justify-center mb-4">
    <img src="{{ asset('images/logo-untar.jpg') }}" alt="Logo Untar" class="h-40">
    </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border @error('email') border-red-500 @else border-gray-300 @enderror
                        rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="nama@email.com" required autofocus>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border @error('password') border-red-500 @else border-gray-300 @enderror
                        rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="••••••••" required>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6 text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded"> Ingat saya
                    </label>
                    <a href="#" class="text-red-600 hover:underline">Lupa password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-red-600 font-medium hover:underline">Daftar sekarang</a>
            </p>
        
        </div>

</body>
</html>
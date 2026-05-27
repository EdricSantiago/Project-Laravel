{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — Digital Banking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10">

<div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8">
    <h1 class="text-2xl font-bold text-blue-700 mb-2">Buka Rekening</h1>
    <p class="text-gray-500 text-sm mb-6">Daftarkan diri Anda untuk mulai bertransaksi</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border @error('name') border-red-500 @else border-gray-300 @enderror
                          rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Sesuai KTP" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- NIK --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 digit)</label>
            <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                   class="w-full border @error('nik') border-red-500 @else border-gray-300 @enderror
                          rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="3201xxxxxxxxxxxxxxx" required>
            @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border @error('email') border-red-500 @else border-gray-300 @enderror
                          rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="nama@email.com" required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- no_hp --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
            <input type="tel" name="no_hp" value="{{ old('no_hp') }}"
                   class="w-full border @error('no_hp') border-red-500 @else border-gray-300 @enderror
                          rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="08123456789" required>
            @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full border @error('password') border-red-500 @else border-gray-300 @enderror
                          rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Min. 8 karakter" required>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Ulangi password" required>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Masuk di sini</a>
    </p>
</div>

</body>
</html>
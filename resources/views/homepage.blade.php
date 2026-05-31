<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage — Bank Untar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-red-700 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Bank Untar</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-white text-red-700 px-4 py-1.5 rounded-lg text-sm font-medium">
            Keluar
        </button>
    </form>
</nav>

<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <p class="text-gray-500 text-">Selamat datang,</p>
        <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>

        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">Saldo Rekening</p>
                <p id="balance-display" class="text-xl font-bold text-gray-900 tracking-wide">
                    Rp {{ number_format($account->balance ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <button onclick="toggleBalance()" id="toggle-btn"
            class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-500 hover:bg-gray-50 transition">
            <svg id="eye-icon" class="w-4 h-4 stroke-current" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
            </svg>
            <span id="toggle-label">Sembunyikan</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-red-600 text-white rounded-2xl p-6">
            <p class="text-sm opacity-80">Nomor Rekening</p>
            <p class="text-xl font-bold mt-1">{{ $user->account_number }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-semibold text-gray-800 mt-1">{{ $user->email }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-gray-500">No. HP</p>
            <p class="font-semibold text-gray-800 mt-1">{{ $user->no_hp }}</p>
        </div>
    </div>
</div>
{{-- Security Center --}}
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-3">
            <span>Security Center </span>
            <span class="text-xs px-2.5 py-1 rounded-full font-bold capitalize {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                Status Akun: {{ $user->status }}
            </span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            {{-- Ubah PIN --}}
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm">Ubah PIN </h3>
                @if($errors->any())
                    <div class="bg-red-100 border border-red-200 text-red-700 px-3 py-2 rounded-lg mb-4 text-xs font-medium animate-pulse">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 border border-green-200 text-green-700 px-3 py-2 rounded-lg mb-4 text-xs font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('security.change') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <input type="password" name="oldPin" placeholder="Masukkan PIN Lama Anda" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-red-500 text-center tracking-widest">
                    </div>
                    <div class="mb-3">
                        <input type="password" name="newPin" placeholder="Buat PIN Baru (6 digit Angka)" required maxlength="6" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-red-500 text-center tracking-widest">
                    </div>
                    <button type="submit" class="w-full bg-gray-800 text-white rounded-lg py-2.5 text-sm font-bold hover:bg-black transition-all shadow">
                        Perbarui PIN 
                    </button>
                </form>
            </div>

            {{-- Freeze Account --}}
            <div class="bg-red-50 rounded-2xl shadow p-6 border border-red-100 flex flex-col justify-center items-center text-center">
                <h3 class="font-bold text-red-700 mb-1 text-base">Freeze Account </h3>
                <p class="text-xs text-red-500 mb-4 max-w-xs">Lakukan tindakan ini jika Anda merasa akun Anda telah diretas atau HP Anda hilang.<br>(Jika ingin membuka blokir akun, silahkan hubungi customer service).</p>
                <form method="POST" action="{{ route('security.panic') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MEMBEKUKAN akun ini? Semua aktivitas keuangan akan diblokir!')" 
                            class="bg-red-600 text-white rounded-full px-6 py-2.5 font-bold shadow-md hover:bg-red-700 hover:scale-105 transition-all text-sm tracking-wide">
                        FREEZE
                    </button>
                </form>
            </div>
        </div>
</div>
    <script>
        let visible = true;
        const realBalance = "Rp {{ number_format($account->balance ?? 0, 0, ',', '.') }}";
        const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        function toggleBalance() {
            visible = !visible;
            const display = document.getElementById('balance-display');
            const icon    = document.getElementById('eye-icon');
            const label   = document.getElementById('toggle-label');

            if (visible) {
                display.textContent = realBalance;
                icon.innerHTML      = eyeOpen;
                label.textContent   = 'Sembunyikan';
            } else {
                display.textContent = '••••••••';
                icon.innerHTML      = eyeClosed;
                label.textContent   = 'Tampilkan';
            }
        }
    </script>
</body>
</html>
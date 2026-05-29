<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage — MyABC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">MyABC</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-white text-blue-700 px-4 py-1.5 rounded-lg text-sm font-medium">
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
        <div class="bg-blue-600 text-white rounded-2xl p-6">
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
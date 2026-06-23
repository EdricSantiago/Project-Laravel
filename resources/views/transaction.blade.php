<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi — Bank Untar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-red-700 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Bank Untar</h1>
    <div class="flex gap-3 items-center">
        <a href="{{ route('homepage') }}" class="text-sm hover:underline">Homepage</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-white text-red-700 px-4 py-1.5 rounded-lg text-sm font-medium">
                Keluar
            </button>
        </form>
    </div>
</nav>

<div class="max-w-4xl mx-auto mt-10 px-4" x-data="{
    activeMenu: null,
    pinModal: false,
    pendingForm: null,
    openPinThen(formId) {
        this.pendingForm = formId;
        this.pinModal = true;
    }
}">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-red-600 text-white rounded-2xl p-6 mb-6">
        <p class="text-sm opacity-80">Saldo Rekening</p>
        <p class="text-3xl font-bold mt-1">
            Rp {{ number_format($account?->balance ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-sm opacity-70 mt-1">No. Rekening: {{ $account?->account_number ?? '-' }}</p>
    </div>

    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mb-8">
        <button @click="activeMenu = (activeMenu === 'transfer' ? null : 'transfer')"
            class="flex flex-col items-center gap-2 bg-white rounded-2xl shadow p-4 hover:bg-red-50 transition"
            :class="activeMenu === 'transfer' ? 'ring-2 ring-red-500' : ''">
            <span class="text-2xl">⇄</span>
            <span class="text-xs font-semibold text-gray-700">Transfer</span>
        </button>

        <button @click="activeMenu = (activeMenu === 'deposit' ? null : 'deposit')"
            class="flex flex-col items-center gap-2 bg-white rounded-2xl shadow p-4 hover:bg-green-50 transition"
            :class="activeMenu === 'deposit' ? 'ring-2 ring-green-500' : ''">
            <span class="text-2xl">＋</span>
            <span class="text-xs font-semibold text-gray-700">Setor</span>
        </button>

        <button @click="activeMenu = (activeMenu === 'withdraw' ? null : 'withdraw')"
            class="flex flex-col items-center gap-2 bg-white rounded-2xl shadow p-4 hover:bg-red-50 transition"
            :class="activeMenu === 'withdraw' ? 'ring-2 ring-red-500' : ''">
            <span class="text-2xl">↓</span>
            <span class="text-xs font-semibold text-gray-700">Tarik</span>
        </button>

        <button @click="activeMenu = (activeMenu === 'history' ? null : 'history')"
            class="flex flex-col items-center gap-2 bg-white rounded-2xl shadow p-4 hover:bg-gray-50 transition"
            :class="activeMenu === 'history' ? 'ring-2 ring-gray-500' : ''">
            <span class="text-2xl">≡</span>
            <span class="text-xs font-semibold text-gray-700">Riwayat</span>
        </button>

        <button @click="activeMenu = (activeMenu === 'qr' ? null : 'qr')"
            class="flex flex-col items-center gap-2 bg-white rounded-2xl shadow p-4 hover:bg-amber-50 transition"
            :class="activeMenu === 'qr' ? 'ring-2 ring-amber-500' : ''">
            <span class="text-2xl">▦</span>
            <span class="text-xs font-semibold text-gray-700">Show My QR</span>
        </button>
    </div>

    <div x-show="activeMenu === 'transfer'" x-cloak class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="font-semibold text-gray-700 mb-4">Transfer</h3>
        <form id="form-transfer" method="POST" action="{{ route('transaction.transfer') }}"
              @submit.prevent="openPinThen('form-transfer')">
            @csrf
            <input type="text" name="receiver_account" placeholder="No. Rekening Tujuan"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                required>
            <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                min="10000" required>
            <button type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                Transfer
            </button>
        </form>
    </div>

    <div x-show="activeMenu === 'deposit'" x-cloak class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="font-semibold text-gray-700 mb-4">Setor (Deposit)</h3>
        <form id="form-deposit" method="POST" action="{{ route('transaction.deposit') }}"
              @submit.prevent="openPinThen('form-deposit')">
            @csrf
            <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                min="10000" required>
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                Setor
            </button>
        </form>
    </div>

    <div x-show="activeMenu === 'withdraw'" x-cloak class="bg-white rounded-2xl shadow p-6 mb-8">
        <h3 class="font-semibold text-gray-700 mb-4">Tarik (Withdraw)</h3>
        <form id="form-withdraw" method="POST" action="{{ route('transaction.withdraw') }}"
              @submit.prevent="openPinThen('form-withdraw')">
            @csrf
            <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                min="10000" required>
            <button type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                Tarik
            </button>
        </form>
    </div>

    <div x-show="activeMenu === 'history'" x-cloak class="bg-white rounded-2xl shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-700">Riwayat Transaksi</h3>
            <a href="{{ route('transaction.exportPdf') }}"
               class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                Export PDF
            </a>
        </div>
        @forelse($transactions as $trx)
            <div class="flex justify-between items-center border-b py-3 text-sm">
                <div>
                    <span class="font-medium capitalize">{{ $trx->type }}</span>
                    <p class="text-gray-400 text-xs">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="{{ $trx->type === 'deposit' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        {{ $trx->type === 'deposit' ? '+' : '-' }}
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-gray-400 capitalize">{{ $trx->status }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm text-center py-4">Belum ada riwayat transaksi.</p>
        @endforelse
    </div>

    <div x-show="activeMenu === 'qr'" x-cloak class="bg-white rounded-2xl shadow p-6 mb-8 text-center">
        <h3 class="font-semibold text-gray-700 mb-3">QRIS Saya</h3>
        <p class="text-xs text-gray-500 mb-4">Tunjukkan QR ini agar orang lain bisa transfer ke rekening Anda</p>

        <img src="{{ asset('images/qrcode.svg') }}"
             alt="QR Code Rekening"
             class="mx-auto rounded-lg border border-gray-200 p-2 w-48 h-48">

        <p class="mt-3 text-sm font-bold text-gray-800">{{ $account?->account_number ?? '-' }}</p>
        <p class="text-xs text-gray-400">{{ $user->name ?? Auth::user()->name }}</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow-md mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Pembayaran Asuransi</h3>
        <p class="text-sm text-gray-600 mb-4">Tagihan Bulanan: <span class="font-bold text-red-500">Rp 100.000</span></p>

        <form action="{{ route('transaction.payInsurance') }}" method="POST"
              @submit.prevent="openPinThen('form-insurance')" id="form-insurance">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                Bayar Sekarang
            </button>
        </form>
    </div>

    <div x-show="pinModal" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm" @click.outside="pinModal = false">
            <h3 class="font-bold text-gray-800 mb-1">Verifikasi PIN</h3>
            <p class="text-xs text-gray-500 mb-4">Masukkan 6-digit PIN transaksi Anda untuk melanjutkan.</p>

            <div x-show="pinError" x-cloak class="bg-red-100 text-red-700 text-xs p-2 rounded-lg mb-3" x-text="pinError"></div>

            <form @submit.prevent="
                pinError = null;
                fetch('{{ route('security.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin: pinInput })
                })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    if (res.ok) {
                        pinModal = false;
                        pinInput = '';
                        document.getElementById(pendingForm).submit();
                    } else {
                        pinError = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'PIN salah, coba lagi.');
                    }
                })
                .catch(() => { pinError = 'Terjadi kesalahan, coba lagi.'; });
            ">
                <input type="password" x-model="pinInput" maxlength="6" placeholder="••••••" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm text-center tracking-widest mb-4 focus:ring-2 focus:ring-amber-500 outline-none">

                <div class="flex gap-2">
                    <button type="button" @click="pinModal = false; pinError = null; pinInput = ''"
                        class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-1/2 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>
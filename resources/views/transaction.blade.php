@extends('layouts.app')

@section('title', 'Transfer - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg" x-data="{
    activeMenu: '{{ request('menu') }}' || null,
    pinModal: false,
    pendingForm: null,
    pinInput: '',
    pinError: null,
    openPinThen(formId) {
        this.pendingForm = formId;
        this.pinModal = true;
        this.pinError = null;
        this.pinInput = '';
    }
}"
x-init="if(window.location.search.includes('menu=')) window.history.replaceState({}, '', window.location.pathname)">
    @include('partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                <div class="flex justify-between items-end mb-2">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Transfer & Transaksi</h1>
                        <p class="text-sm text-gray-400 font-medium mt-1">Kelola transaksi dan riwayat rekening Anda.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-bank-red px-4 py-3 rounded-xl text-sm font-medium space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="relative overflow-hidden bg-white rounded-2xl p-8 border border-bank-border group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-100 rounded-full blur-3xl opacity-30 -mr-20 -mt-20 pointer-events-none transition-opacity group-hover:opacity-50"></div>

                    <div class="relative z-10">
                        <p class="text-sm text-gray-400 font-medium mb-2">Saldo Rekening</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-gray-900">Rp</span>
                            <span class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ number_format($account?->balance ?? 0, 2, ',', '.') }}</span>
                        </div>
                        <p class="text-sm font-mono text-gray-400 tracking-wider mt-3">No. Rekening: {{ $account?->account_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    <button @click="activeMenu = (activeMenu === 'transfer' ? null : 'transfer')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-red-50/50 transition-all"
                        :class="activeMenu === 'transfer' ? 'ring-2 ring-bank-red border-bank-red' : ''">
                        <i class="material-icons-outlined text-bank-red text-[22px]">swap_horiz</i>
                        <span class="text-xs font-semibold text-gray-700">Transfer</span>
                    </button>

                    <button @click="activeMenu = (activeMenu === 'deposit' ? null : 'deposit')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-emerald-50/50 transition-all"
                        :class="activeMenu === 'deposit' ? 'ring-2 ring-emerald-500 border-emerald-500' : ''">
                        <i class="material-icons-outlined text-emerald-600 text-[22px]">add_circle_outline</i>
                        <span class="text-xs font-semibold text-gray-700">Setor</span>
                    </button>

                    <button @click="activeMenu = (activeMenu === 'withdraw' ? null : 'withdraw')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-red-50/50 transition-all"
                        :class="activeMenu === 'withdraw' ? 'ring-2 ring-bank-red border-bank-red' : ''">
                        <i class="material-icons-outlined text-bank-red text-[22px]">arrow_downward</i>
                        <span class="text-xs font-semibold text-gray-700">Tarik</span>
                    </button>

                    <button @click="activeMenu = (activeMenu === 'history' ? null : 'history')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-gray-50 transition-all"
                        :class="activeMenu === 'history' ? 'ring-2 ring-gray-400 border-gray-400' : ''">
                        <i class="material-icons-outlined text-gray-600 text-[22px]">receipt_long</i>
                        <span class="text-xs font-semibold text-gray-700">Riwayat</span>
                    </button>

                    <button @click="activeMenu = (activeMenu === 'qr' ? null : 'qr')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-amber-50/50 transition-all"
                        :class="activeMenu === 'qr' ? 'ring-2 ring-amber-500 border-amber-500' : ''">
                        <i class="material-icons-outlined text-amber-600 text-[22px]">qr_code_2</i>
                        <span class="text-xs font-semibold text-gray-700">Show My QR</span>
                    </button>

                    <button @click="activeMenu = (activeMenu === 'insurance' ? null : 'insurance')"
                        class="flex flex-col items-center gap-2 bg-white rounded-2xl border border-bank-border p-4 hover:bg-blue-50/50 transition-all"
                        :class="activeMenu === 'insurance' ? 'ring-2 ring-blue-500 border-blue-500' : ''">
                        <i class="material-icons-outlined text-blue-600 text-[22px]">health_and_safety</i>
                        <span class="text-xs font-semibold text-gray-700">Asuransi</span>
                    </button>
                </div>

                <div x-show="activeMenu === 'transfer'" x-cloak class="bg-white rounded-2xl border border-bank-border p-6">
                    <h3 class="font-extrabold text-gray-900 mb-4">Transfer ke Rekening Lain</h3>
                    <form id="form-transfer" method="POST" action="{{ route('transaction.transfer') }}"
                          @submit.prevent="openPinThen('form-transfer')" class="space-y-3">
                        @csrf
                        <input type="text" name="receiver_account" placeholder="No. Rekening Tujuan"
                            class="w-full border border-bank-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bank-red"
                            required>
                        <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                            class="w-full border border-bank-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bank-red"
                            min="10000" required>
                        <button type="submit"
                            class="w-full bg-bank-red hover:bg-bank-red-light text-white font-bold py-2.5 rounded-xl text-sm transition">
                            Transfer Sekarang
                        </button>
                    </form>
                </div>

                <div x-show="activeMenu === 'deposit'" x-cloak class="bg-white rounded-2xl border border-bank-border p-6">
                    <h3 class="font-extrabold text-gray-900 mb-4">Setor Saldo (Deposit)</h3>
                    <form id="form-deposit" method="POST" action="{{ route('transaction.deposit') }}"
                          @submit.prevent="openPinThen('form-deposit')" class="space-y-3">
                        @csrf
                        <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                            class="w-full border border-bank-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            min="10000" required>
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                            Setor Sekarang
                        </button>
                    </form>
                </div>

                <div x-show="activeMenu === 'withdraw'" x-cloak class="bg-white rounded-2xl border border-bank-border p-6">
                    <h3 class="font-extrabold text-gray-900 mb-4">Tarik Saldo (Withdraw)</h3>
                    <form id="form-withdraw" method="POST" action="{{ route('transaction.withdraw') }}"
                          @submit.prevent="openPinThen('form-withdraw')" class="space-y-3">
                        @csrf
                        <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                            class="w-full border border-bank-border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-bank-red"
                            min="10000" required>
                        <button type="submit"
                            class="w-full bg-bank-red hover:bg-bank-red-light text-white font-bold py-2.5 rounded-xl text-sm transition">
                            Tarik Sekarang
                        </button>
                    </form>
                </div>

                <div x-show="activeMenu === 'history'" x-cloak class="bg-white rounded-2xl border border-bank-border p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-extrabold text-gray-900">Riwayat Transaksi</h3>
                        <a href="{{ route('transaction.exportPdf') }}"
                           class="inline-flex items-center gap-2 bg-bank-red hover:bg-bank-red-light text-white text-sm font-bold px-4 py-2 rounded-xl transition">
                            <i class="material-icons-outlined text-[16px]">download</i> Export PDF
                        </a>
                    </div>
                    @forelse($transactions as $trx)
                        <div class="flex justify-between items-center border-b border-bank-border py-3 text-sm">
                            <div>
                                <span class="font-semibold text-gray-800 capitalize">{{ $trx->type }}</span>
                                <p class="text-gray-400 text-xs">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="{{ $trx->type === 'deposit' ? 'text-emerald-600' : 'text-bank-red' }} font-bold">
                                    {{ $trx->type === 'deposit' ? '+' : '-' }}
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </span>
                                <p class="text-xs text-gray-400 capitalize">{{ $trx->status }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="material-icons-outlined text-gray-300 text-[40px]">receipt_long</i>
                            <p class="text-gray-400 text-sm mt-2">Belum ada riwayat transaksi.</p>
                        </div>
                    @endforelse
                </div>

                <div x-show="activeMenu === 'qr'" x-cloak class="bg-white rounded-2xl border border-bank-border p-8 text-center">
                    <h3 class="font-extrabold text-gray-900 mb-1">QRIS Saya</h3>
                    <p class="text-xs text-gray-400 mb-5">Tunjukkan QR ini agar orang lain bisa transfer ke rekening Anda</p>

                    <div class="inline-block bg-white p-3 rounded-2xl border border-bank-border">
                        <img src="{{ asset('images/qrcode.svg') }}"
                             alt="QR Code Rekening"
                             class="w-48 h-48">
                    </div>

                    <p class="mt-4 text-sm font-bold text-gray-900 font-mono tracking-wider">{{ $account?->account_number ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $user->name ?? Auth::user()->name }}</p>
                </div>

                <div x-show="activeMenu === 'insurance'" x-cloak class="bg-white rounded-2xl border border-bank-border p-6">
                    <h3 class="font-extrabold text-gray-900 mb-1">Pembayaran Asuransi</h3>
                    <p class="text-sm text-gray-400 mb-4">Tagihan Bulanan: <span class="font-bold text-bank-red">Rp 100.000</span></p>

                    <form action="{{ route('transaction.payInsurance') }}" method="POST"
                          @submit.prevent="openPinThen('form-insurance')" id="form-insurance">
                        @csrf
                        <button type="submit" class="bg-bank-red hover:bg-bank-red-light text-white font-bold px-5 py-2.5 rounded-xl text-sm transition">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <div x-show="pinModal" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm" @click.outside="pinModal = false">
            <h3 class="font-extrabold text-gray-900 mb-1">Verifikasi PIN</h3>
            <p class="text-xs text-gray-400 mb-4">Masukkan 6-digit PIN transaksi Anda untuk melanjutkan.</p>

            <div x-show="pinError" x-cloak class="bg-red-50 text-bank-red text-xs p-2.5 rounded-xl mb-3 border border-red-200" x-text="pinError"></div>

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
                    class="w-full border border-bank-border rounded-xl px-4 py-2.5 text-sm text-center tracking-widest mb-4 focus:ring-2 focus:ring-bank-red outline-none">

                <div class="flex gap-2">
                    <button type="button" @click="pinModal = false; pinError = null; pinInput = ''"
                        class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-1/2 bg-bank-red hover:bg-bank-red-light text-white font-bold py-2.5 rounded-xl text-sm transition">
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

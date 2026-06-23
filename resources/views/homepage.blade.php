@extends('layouts.app')

@section('title', 'Homepage - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        @include('partials.topnav')

        <!-- Scrollable Content Area -->
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Balance Card -->
                <div class="bg-white rounded-2xl border border-bank-border p-8 relative overflow-hidden">
                    <!-- Decorative circle -->
                    <div class="absolute -top-8 -right-8 w-40 h-40 bg-red-50 rounded-full opacity-60"></div>
                    <div
                        class="absolute top-6 right-6 w-14 h-14 bg-red-100/50 rounded-xl flex items-center justify-center">
                        <i class="material-icons text-red-200 text-2xl">visibility_off</i>
                    </div>

                    <div class="relative">
                        <p class="text-sm text-gray-400 font-medium mb-3">Total Balance</p>
                        <div class="flex items-center gap-3 mb-8">
                            <span
                                class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded-md tracking-wider">IDR</span>
                            <span id="balance-display" class="text-4xl font-extrabold text-gray-900 tracking-tight">Rp
                                ***</span>
                            <button onclick="toggleBalance()" id="toggle-btn"
                                class="text-gray-400 hover:text-bank-red transition-colors ml-1">
                                <i class="material-icons text-xl" id="eye-icon">visibility_off</i>
                            </button>
                        </div>

                        <div class="flex gap-16">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-1">Account Number</p>
                                <p class="text-xl font-extrabold text-gray-900 tracking-wide">
                                    {{ Auth::user()->account_number ?? (Auth::user()->account->account_number ?? '-') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-1">Account Holder</p>
                                <p class="text-xl font-extrabold text-gray-900 uppercase">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email & Phone Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl border border-bank-border p-6">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-4">
                            <i class="material-icons text-bank-red text-xl">email</i>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-1">Registered Email</p>
                        <p class="text-base font-bold text-gray-900">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-bank-border p-6">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                            <i class="material-icons text-blue-500 text-xl">smartphone</i>
                        </div>
                        <p class="text-xs text-gray-400 font-medium mb-1">Registered Phone</p>
                        <p class="text-base font-bold text-gray-900">{{ Auth::user()->no_hp ?? '-' }}</p>
                    </div>
                </div>

                <!-- Quick Actions & Transaction History -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl border border-bank-border p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                            <a href="{{ route('transaction.index') }}"
                                class="text-gray-300 hover:text-bank-red transition-colors">
                                <i class="material-icons text-xl">chevron_right</i>
                            </a>
                        </div>
                        <div class="grid grid-cols-4 gap-3">
                            <a href="{{ route('transaction.index') }}" class="flex flex-col items-center gap-2 group">
                                <div
                                    class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                    <i class="material-icons text-bank-red text-xl">swap_horiz</i>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500 text-center">Transfer</span>
                            </a>
                            <a href="#" class="flex flex-col items-center gap-2 group">
                                <div
                                    class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                                    <i class="material-icons text-amber-600 text-xl">account_balance</i>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500 text-center">Deposito</span>
                            </a>
                            <a href="{{ route('investasi.index') }}" class="flex flex-col items-center gap-2 group">
                                <div
                                    class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                                    <i class="material-icons text-rose-500 text-xl">trending_up</i>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500 text-center">Portofolio</span>
                            </a>
                            <a href="{{ route('transaction.history') }}" class="flex flex-col items-center gap-2 group">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                                    <i class="material-icons text-gray-500 text-xl">receipt</i>
                                </div>
                                <span
                                    class="text-[11px] font-semibold text-gray-500 text-center leading-tight">E-Statement</span>
                            </a>
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.pinjaman.index') : route('pinjaman.index') }}"
                                class="flex flex-col items-center gap-2 group">
                                <div
                                    class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                    <i class="material-icons text-bank-red text-xl">account_balance_wallet</i>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500 text-center leading-tight">Pinjaman</span>
                            </a>
                        </div>
                    </div>

                    <!-- Transaction History -->
                    <div class="bg-white rounded-2xl border border-bank-border p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h3>
                            <a href="{{ route('transaction.history') }}"
                                class="text-gray-300 hover:text-bank-red transition-colors">
                                <i class="material-icons text-xl">chevron_right</i>
                            </a>
                        </div>
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i class="material-icons text-gray-200 text-5xl mb-3">history</i>
                            <p class="text-sm text-gray-300 font-medium">Belum ada transaksi terbaru</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let visible = false;
    const realBalance = "Rp {{ number_format($account->balance ?? 0, 0, ',', '.') }}";

    function toggleBalance() {
        visible = !visible;
        const display = document.getElementById('balance-display');
        const icon = document.getElementById('eye-icon');

        if (visible) {
            display.textContent = realBalance;
            icon.textContent = 'visibility';
        } else {
            display.textContent = 'Rp ***';
            icon.textContent = 'visibility_off';
        }
    }
</script>
@endpush
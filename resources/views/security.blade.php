@extends('layouts.app')

@section('title', 'Security Center - Bank Untar')

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
                <div class="max-w-5xl mx-auto">

                    <!-- Security Center Header -->
                    <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                        <div class="bg-gray-50/50 px-8 py-4 border-b border-bank-border flex items-center gap-3">
                            <i class="material-icons text-bank-red text-xl">security</i>
                            <h2 class="text-lg font-bold text-gray-900">Security Center</h2>
                        </div>

                        <div class="p-12 flex flex-col lg:flex-row gap-12">
                            <!-- Update PIN & Password Section -->
                            <div class="flex-1 space-y-8">
                                
                                <!-- Bagian Update PIN -->
                                <div>
                                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Update PIN</h3>
                                    <p class="text-gray-500 leading-relaxed max-w-md">
                                        Ensure your account stays secure by regularly updating your transaction PIN. Keep it
                                        unique and hard to guess.
                                    </p>
                                </div>

                                <form action="{{ route('security.change') }}" method="POST" class="space-y-4 max-w-md">
                                    @csrf
                                    @if($errors->any())
                                        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium animate-pulse">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div>
                                        <input type="password" name="oldPin" placeholder="Old PIN (6 Digits)" required
                                            class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                    </div>
                                    <div>
                                        <input type="password" name="newPin" placeholder="New PIN (6 Digits)" required
                                            class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-bank-red text-white py-4 rounded-xl font-bold hover:bg-red-900 transition-all active:scale-[0.98] shadow-lg shadow-red-100">
                                        Perbarui PIN
                                    </button>
                                </form>

                                <!-- Bagian Update Password -->
                                <div class="pt-8 mt-8 border-t border-gray-100">
                                    <div>
                                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Update Password</h3>
                                        <p class="text-gray-500 leading-relaxed max-w-md mb-6">
                                            For enhanced security, it's recommended to change your account password
                                            periodically. Use a strong combination of letters, numbers, and symbols.
                                        </p>
                                    </div>

                                    @if($errors->any())
                                        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium animate-pulse max-w-md">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium max-w-md">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <form action="{{ route('security.password') }}" method="POST" class="space-y-4 max-w-md">
                                        @csrf
                                        <div>
                                            <input type="password" name="oldPassword" placeholder="Password Lama" required
                                                class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                        </div>
                                        <div>
                                            <input type="password" name="newPassword" placeholder="Password Baru" required
                                                class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-bank-red text-white py-4 rounded-xl font-bold hover:bg-red-900 transition-all active:scale-[0.98] shadow-lg shadow-red-100">
                                            Perbarui Password
                                        </button>
                                    </form>
                                </div>

                            </div>

                            <!-- Vertical Divider -->
                            <div class="hidden lg:block w-[1px] bg-gray-100"></div>

                            <!-- Freeze Account Section -->
                            <div class="flex-1">
                                <div
                                    class="bg-red-50/30 border border-red-100 rounded-3xl p-10 h-full flex flex-col items-center text-center">
                                    <div
                                        class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bank-red mb-8">
                                        <i class="material-icons text-3xl">report_problem</i>
                                    </div>

                                    <h3 class="text-2xl font-bold text-bank-red mb-6">Freeze Account</h3>

                                    <form action="{{ route('security.panic') }}" method="POST" class="w-full max-w-xs mt-auto"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membekukan akun? Semua transaksi akan diblokir.')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-bank-red text-white py-5 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-red-900 transition-all active:scale-95 shadow-xl shadow-red-200">
                                            <i class="material-icons">ac_unit</i> FREEZE ACCOUNT
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection

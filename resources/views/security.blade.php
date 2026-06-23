@extends('layouts.app')

@section('title', 'Security Center - Bank Untar')

@section('content')
    <div class="flex h-screen bg-bank-bg">
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.topnav')

            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

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
                                        Pastikan akun Anda tetap aman dengan rutin memperbarui PIN transaksi. Gunakan PIN yang unik dan sulit ditebak.
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
                                        <input type="password" name="oldPin" placeholder="PIN Lama (6 Digit)" required
                                            class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                    </div>
                                    <div>
                                        <input type="password" name="newPin" placeholder="PIN Baru (6 Digit)" required
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
                                            Untuk keamanan akun, disarankan mengganti password akun Anda secara berkala. Gunakan kombinasi huruf, angka, dan simbol yang kuat.
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
                                <div class="bg-red-50/30 border border-red-100 rounded-3xl p-10 h-full flex flex-col items-center text-center">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bank-red mb-8">
                                        <i class="material-icons text-3xl">report_problem</i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-bank-red mb-4">Freeze Akun</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed mb-8">
                                        Jika Anda mencurigai adanya aktivitas tidak sah, segera bekukan akun Anda. Semua transaksi akan diblokir.
                                    </p>
                                    <form action="{{ route('security.panic') }}" method="POST" class="w-full max-w-xs mt-auto"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membekukan akun? Semua transaksi akan diblokir.')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-bank-red text-white py-5 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-red-900 transition-all active:scale-95 shadow-xl shadow-red-200">
                                            <i class="material-icons">ac_unit</i> Freeze Account
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Log Section — di luar flex-row, full width -->
                    <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                        <div class="bg-gray-50/50 px-8 py-4 border-b border-bank-border flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="material-icons text-bank-red text-xl">history</i>
                                <h2 class="text-lg font-bold text-gray-900">Security Logs</h2>
                            </div>


                        <div class="p-6">
                            @if($logs->isEmpty())
                                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                                    <i class="material-icons text-5xl mb-3">shield</i>
                                    <p class="text-sm">Belum ada aktivitas keamanan tercatat.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-bank-border text-left">
                                                <th class="pb-3 pr-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Aksi</th>
                                                <th class="pb-3 pr-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Status</th>
                                                <th class="pb-3 pr-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">IP Address</th>
                                                <th class="pb-3 pr-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Perangkat</th>
                                                <th class="pb-3 pr-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Keterangan</th>
                                                <th class="pb-3 font-semibold text-gray-500 text-xs uppercase tracking-wide">Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-bank-border">
                                            @foreach($logs as $log)
                                                <tr class="hover:bg-gray-50/60 transition-colors">
                                                    <td class="py-4 pr-4">
                                                        @php
                                                            $badgeColor = match(true) {
                                                                str_contains($log->action, 'FAILED')  => 'bg-amber-50 text-amber-700 border-amber-200',
                                                                str_contains($log->action, 'SUSPEND') => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'FROZEN')  => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'LOCKED')  => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'SUCCESS') => 'bg-green-50 text-green-700 border-green-200',
                                                                str_contains($log->action, 'LOGIN')   => 'bg-blue-50 text-blue-700 border-blue-200',
                                                                default                               => 'bg-gray-50 text-gray-600 border-gray-200',
                                                            };
                                                        @endphp
                                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium border {{ $badgeColor }} whitespace-nowrap">
                                                            {{ $log->action }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 pr-4">
                                                        @if($log->status === 'success')
                                                            <span class="flex items-center gap-1 text-green-600">
                                                                <i class="material-icons text-sm">check_circle</i>
                                                                <span class="text-xs font-medium">Berhasil</span>
                                                            </span>
                                                        @else
                                                            <span class="flex items-center gap-1 text-red-500">
                                                                <i class="material-icons text-sm">cancel</i>
                                                                <span class="text-xs font-medium">Gagal</span>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 pr-4">
                                                        <span class="font-mono text-xs text-gray-600">{{ $log->ip_address ?? '-' }}</span>
                                                    </td>
                                                    <td class="py-4 pr-4">
                                                        <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                                            <i class="material-icons text-sm text-gray-400">
                                                                {{ $log->device_type === 'mobile' ? 'smartphone' : 'computer' }}
                                                            </i>
                                                            {{ ucfirst($log->device_type ?? 'desktop') }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 pr-4">
                                                        <span class="text-xs text-gray-500">{{ $log->notes ?? '-' }}</span>
                                                    </td>
                                                    <td class="py-4">
                                                        <div class="text-xs text-gray-500">
                                                            <div class="font-medium text-gray-700">{{ $log->created_at->format('d M Y') }}</div>
                                                            <div>{{ $log->created_at->format('H:i:s') }}</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($logs->hasPages())
                                    <div class="mt-6 pt-4 border-t border-bank-border flex justify-center">
                                        {{ $logs->links() }}
                                    </div>
                                @endif

                                <p class="text-xs text-gray-400 text-center mt-4">
                                    Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
                                </p>
                            @endif
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection

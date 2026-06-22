@extends('layouts.app')

@section('title', 'Security Log - Bank Untar')

@section('content')
    <div class="flex h-screen bg-bank-bg">
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials.topnav')

            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto">

                    <!-- Header -->
                    <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                        <div class="bg-gray-50/50 px-8 py-4 border-b border-bank-border flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="material-icons text-bank-red text-xl">history</i>
                                <h2 class="text-lg font-bold text-gray-900">Security Log</h2>
                            </div>
                            <span class="text-xs text-gray-400">Riwayat aktivitas keamanan akun Anda</span>
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
                                                    <!-- Action Badge -->
                                                    <td class="py-4 pr-4">
                                                        @php
                                                            $badgeColor = match(true) {
                                                                str_contains($log->action, 'FAILED')   => 'bg-amber-50 text-amber-700 border-amber-200',
                                                                str_contains($log->action, 'SUSPEND')  => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'FROZEN')   => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'LOCKED')   => 'bg-red-50 text-red-700 border-red-200',
                                                                str_contains($log->action, 'SUCCESS')  => 'bg-green-50 text-green-700 border-green-200',
                                                                str_contains($log->action, 'LOGIN')    => 'bg-blue-50 text-blue-700 border-blue-200',
                                                                default                                => 'bg-gray-50 text-gray-600 border-gray-200',
                                                            };
                                                        @endphp
                                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium border {{ $badgeColor }} whitespace-nowrap">
                                                            {{ $log->action }}
                                                        </span>
                                                    </td>

                                                    <!-- Status -->
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

                                                    <!-- IP Address -->
                                                    <td class="py-4 pr-4">
                                                        <span class="font-mono text-xs text-gray-600">{{ $log->ip_address ?? '-' }}</span>
                                                    </td>

                                                    <!-- Device -->
                                                    <td class="py-4 pr-4">
                                                        <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                                            <i class="material-icons text-sm text-gray-400">
                                                                {{ $log->device_type === 'mobile' ? 'smartphone' : 'computer' }}
                                                            </i>
                                                            {{ ucfirst($log->device_type ?? 'desktop') }}
                                                        </span>
                                                    </td>

                                                    <!-- Notes -->
                                                    <td class="py-4 pr-4">
                                                        <span class="text-xs text-gray-500">{{ $log->notes ?? '-' }}</span>
                                                    </td>

                                                    <!-- Time -->
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

                                <!-- Pagination -->
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

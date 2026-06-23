@extends('layouts.app')

@section('title', 'Review Pinjaman - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto space-y-6">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <a href="{{ route('admin.pinjaman.index') }}" class="hover:text-bank-red transition">Kelola Pinjaman</a>
                    <i class="material-icons text-xs">chevron_right</i>
                    <span class="text-gray-600">Review #{{ $pinjaman->id }}</span>
                </div>

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
                @endif
                <div class="bg-white rounded-2xl border border-bank-border p-6">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="material-icons text-bank-red text-xl">person</i>
                    </div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Data Nasabah</p>
                    <p class="font-extrabold text-gray-900 text-base">{{ $pinjaman->account->user->name ?? '-' }}</p>
                    <p class="text-sm text-gray-400 mt-0.5">No. Rekening: {{ $pinjaman->account->account_number ?? '-' }}</p>
                </div>
                <div class="bg-bank-red rounded-2xl p-6 text-white">
                    <p class="text-red-200 text-xs uppercase tracking-wide font-medium mb-1">Cicilan per Bulan</p>
                    <p class="text-3xl font-extrabold">{{ $pinjaman->formatted_monthly_installment }}</p>
                    <div class="mt-4 flex gap-8 text-sm">
                        <div>
                            <p class="text-red-300 text-xs font-medium mb-0.5">Jumlah Pinjaman</p>
                            <p class="font-bold">{{ $pinjaman->formatted_amount }}</p>
                        </div>
                        <div>
                            <p class="text-red-300 text-xs font-medium mb-0.5">Tenor</p>
                            <p class="font-bold">{{ $pinjaman->tenor_months }} Bulan</p>
                        </div>
                        <div>
                            <p class="text-red-300 text-xs font-medium mb-0.5">Total Bayar</p>
                            <p class="font-bold">{{ $pinjaman->formatted_total_repayment }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-bank-border">
                        <h2 class="text-sm font-bold text-gray-700">Detail Ajuan Pinjaman #{{ $pinjaman->id }}</h2>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tujuan</span>
                            <span class="text-sm text-gray-800 font-semibold">{{ $pinjaman->purpose }}</span>
                        </div>
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Bunga</span>
                            <span class="text-sm text-gray-800">{{ $pinjaman->interest_rate }}% / bulan</span>
                        </div>
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Ajuan</span>
                            <span class="text-sm text-gray-800">{{ $pinjaman->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Status</span>
                            @php
                            $badge = match($pinjaman->status) {
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'rejected' => 'bg-red-100 text-red-600',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default => 'bg-gray-100 text-gray-500',
                            };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ $pinjaman->status_label }}
                            </span>
                        </div>

                        @if($pinjaman->status === 'approved' && $pinjaman->approved_at)
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Disetujui Pada</span>
                            <span class="text-sm text-green-700 font-medium">{{ \Carbon\Carbon::parse($pinjaman->approved_at)->format('d M Y, H:i') }}</span>
                        </div>
                        @endif

                        @if($pinjaman->status === 'rejected')
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Ditolak Pada</span>
                            <span class="text-sm text-red-600 font-medium">{{ \Carbon\Carbon::parse($pinjaman->rejected_at)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="px-6 py-3.5 flex justify-between items-start">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide mt-0.5">Alasan Penolakan</span>
                            <span class="text-sm text-red-600 max-w-xs text-right">{{ $pinjaman->rejection_reason }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @if($pinjaman->status === 'pending')
                <div class="bg-white rounded-2xl border border-bank-border p-6 space-y-4">
                    <p class="text-sm font-bold text-gray-700">Keputusan Admin</p>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.pinjaman.approve', $pinjaman) }}" method="POST"
                            onsubmit="return confirm('Setujui pinjaman ini?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-bank-red hover:bg-red-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm">
                                <i class="material-icons text-base">check_circle</i>
                                Setujui
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('modal-tolak').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 border border-red-200 text-red-600 hover:bg-red-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            <i class="material-icons text-base">cancel</i>
                            Tolak
                        </button>
                    </div>
                </div>
                @endif
                <a href="{{ route('admin.pinjaman.index') }}"
                    class="inline-flex items-center gap-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm transition">
                    <i class="material-icons text-base">arrow_back</i>
                    Kembali ke Daftar
                </a>

            </div>
        </main>
    </div>
</div>
<div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl border border-bank-border w-full max-w-md p-6 space-y-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="material-icons text-bank-red">cancel</i>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900">Tolak Pinjaman</h2>
                <p class="text-xs text-gray-400">Alasan ini akan dicatat dan dilihat oleh nasabah.</p>
            </div>
        </div>

        @error('rejection_reason')
        <p class="text-xs text-red-500">{{ $message }}</p>
        @enderror

        <form action="{{ route('admin.pinjaman.reject', $pinjaman) }}" method="POST">
            @csrf @method('PATCH')
            <textarea name="rejection_reason" rows="3" required
                placeholder="cth. Riwayat kredit tidak memenuhi syarat..."
                class="w-full text-sm rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-bank-red transition resize-none mb-4">{{ old('rejection_reason') }}</textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('modal-tolak').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-bank-red hover:bg-red-800 rounded-xl transition">
                    Tolak Pinjaman
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->has('rejection_reason'))
@push('scripts')
<script>
    document.getElementById('modal-tolak').classList.remove('hidden')
</script>
@endpush
@endif

@endsection
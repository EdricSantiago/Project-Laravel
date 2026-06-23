@extends('layouts.app')

@section('title', 'Detail Pinjaman - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-lg mx-auto space-y-6">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <a href="{{ route('pinjaman.index') }}" class="hover:text-bank-red transition">Pinjaman</a>
                    <i class="material-icons text-xs">chevron_right</i>
                    <span class="text-gray-600">Detail</span>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">Detail Pinjaman</h1>
                        <p class="text-sm text-gray-400 mt-0.5">Diajukan pada {{ $pinjaman->created_at->format('d M Y') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $pinjaman->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $pinjaman->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $pinjaman->status === 'rejected' ? 'bg-red-100 text-red-600' : '' }}
                        {{ $pinjaman->status === 'lunas'    ? 'bg-blue-100 text-blue-700' : '' }}
                    ">{{ $pinjaman->status_label }}</span>
                </div>

                {{-- Summary Banner --}}
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

                {{-- Detail Card --}}
                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-bank-border">
                        <h2 class="text-sm font-bold text-gray-700">Informasi Pinjaman</h2>
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
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Pengajuan</span>
                            <span class="text-sm text-gray-800">{{ $pinjaman->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="px-6 py-3.5 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $pinjaman->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $pinjaman->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $pinjaman->status === 'rejected' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $pinjaman->status === 'lunas'    ? 'bg-blue-100 text-blue-700' : '' }}
                            ">{{ $pinjaman->status_label }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <a href="{{ route('pinjaman.index') }}"
                        class="inline-flex items-center gap-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm transition">
                        <i class="material-icons text-base">arrow_back</i>
                        Kembali
                    </a>
                    @if($pinjaman->status === 'pending')
                    <a href="{{ route('pinjaman.edit', $pinjaman) }}"
                        class="border border-red-200 text-bank-red hover:bg-red-50 px-4 py-2 rounded-xl text-sm transition">Edit</a>
                    <form action="{{ route('pinjaman.destroy', $pinjaman) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin hapus pinjaman ini?')">
                        @csrf @method('DELETE')
                        <button class="border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-xl text-sm transition">Hapus</button>
                    </form>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Investasi Saham')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Saham</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih saham dan mulai investasi Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('homepage') }}"
                class="inline-flex items-center gap-2 border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Homepage
            </a>
            @if (auth()->user()->role === 'admin')
            <a href="{{ route('saham.create') }}"
                class="inline-flex items-center gap-2 bg-[#7C1F33] hover:bg-[#69182A] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Saham
            </a>
            @else
            <a href="{{ route('investasi.index') }}"
                class="inline-flex items-center gap-2 border border-[#7C1F33] text-[#7C1F33] hover:bg-[#FCE9ED] text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                Portofolio Saya
            </a>
            @endif
        </div>
    </div>

    @if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg border border-green-100">
        {{ session('success') }}
    </div>
    @endif

    @if ($sahams->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-16 text-gray-400">
        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z" />
        </svg>
        <p class="text-sm">Belum ada saham yang tersedia</p>
    </div>
    @else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($sahams as $saham)
        <a href="{{ route('saham.show', $saham) }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-[#7C1F33]/30 transition block">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $saham->sektor ?? 'Saham' }}</p>
                    <p class="text-lg font-bold text-gray-800">{{ $saham->kode_saham }}</p>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#FCE9ED] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#7C1F33]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13" />
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $saham->nama_perusahaan }}</p>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400">Harga / Lembar</p>
                    <p class="text-base font-semibold text-gray-800">Rp {{ number_format($saham->harga_saat_ini, 0, ',', '.') }}</p>
                </div>
                <span class="text-sm font-semibold text-[#7C1F33]">Invest &rarr;</span>
            </div>
        </a>
        @endforeach
    </div>

    @if ($sahams->hasPages())
    <div>{{ $sahams->links() }}</div>
    @endif
    @endif
</div>
@endsection
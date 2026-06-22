@extends('layouts.app')

@section('title', 'Detail Investasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('investasi.index') }}" class="text-sm text-gray-500 hover:text-[#7C1F33] flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Portofolio
    </a>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $investasi->saham->sektor ?? 'Saham' }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $investasi->saham->kode_saham }}</p>
                <p class="text-base text-gray-600 mt-1">{{ $investasi->saham->nama_perusahaan }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-medium
                {{ $investasi->status === 'aktif' ? 'bg-[#FCE9ED] text-[#7C1F33]' : 'bg-gray-100 text-gray-500' }}">
                {{ ucfirst($investasi->status) }}
            </span>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Jumlah Lembar</p>
            <p class="text-lg font-semibold text-gray-800">{{ number_format($investasi->jumlah_lembar, 0, ',', '.') }} lembar</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Harga Beli per Lembar</p>
            <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($investasi->harga_beli, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Tanggal Beli</p>
            <p class="text-lg font-semibold text-gray-800">{{ $investasi->tanggal_beli->translatedFormat('d F Y') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Harga Saat Ini</p>
            <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($investasi->saham->harga_saat_ini, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Total Modal Awal</p>
            <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($investasi->total_investasi, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-400 mb-2">Nilai Sekarang</p>
            <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($investasi->nilai_sekarang, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:col-span-2">
            <p class="text-sm text-gray-400 mb-2">Keuntungan / Kerugian</p>
            <p class="text-2xl font-bold {{ $investasi->keuntungan >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $investasi->keuntungan >= 0 ? '+' : '-' }} Rp {{ number_format(abs($investasi->keuntungan), 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Detail Saham')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('saham.index') }}" class="text-sm text-gray-500 hover:text-[#7C1F33] flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Saham
    </a>

    @if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg border border-green-100">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg border border-red-100">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $saham->sektor ?? 'Saham' }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $saham->kode_saham }}</p>
                <p class="text-base text-gray-600 mt-1">{{ $saham->nama_perusahaan }}</p>
            </div>

            @if (auth()->user()->role === 'admin')
            <div class="flex gap-3">
                <a href="{{ route('saham.edit', $saham) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg border border-[#7C1F33] text-[#7C1F33] hover:bg-[#FCE9ED] transition">
                    Edit
                </a>
                <form action="{{ route('saham.destroy', $saham) }}" method="POST" onsubmit="return confirm('Hapus saham ini dari daftar?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                        Hapus
                    </button>
                </form>
            </div>
            @endif
        </div>

        @if ($saham->deskripsi)
        <p class="text-sm text-gray-600 mt-4 pt-4 border-t border-gray-100">{{ $saham->deskripsi }}</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-sm text-gray-400 mb-1">Harga Saat Ini</p>
        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($saham->harga_saat_ini, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">/ lembar</span></p>
    </div>

    @if (auth()->user()->role !== 'admin')
    <form action="{{ route('investasi.store', $saham) }}" method="POST"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        <h2 class="text-lg font-bold text-gray-800">Invest Sekarang</h2>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Jumlah Lembar</label>
            <input type="number" name="jumlah_lembar" min="1" value="1"
                oninput="document.getElementById('total-invest').innerText = 'Rp ' + (this.value * {{ $saham->harga_saat_ini }}).toLocaleString('id-ID')"
                class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">
            @error('jumlah_lembar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
            <span class="text-sm text-gray-500">Estimasi Total</span>
            <span id="total-invest" class="text-lg font-bold text-gray-800">Rp {{ number_format($saham->harga_saat_ini, 0, ',', '.') }}</span>
        </div>

        <button type="submit"
            class="w-full bg-[#7C1F33] hover:bg-[#69182A] text-white text-sm font-semibold px-5 py-3 rounded-lg transition">
            Invest Sekarang
        </button>
        <p class="text-xs text-gray-400 text-center">Saldo akan terpotong otomatis dari rekening Anda</p>
    </form>
    @endif
</div>
@endsection
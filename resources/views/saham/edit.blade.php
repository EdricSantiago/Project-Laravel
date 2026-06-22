@extends('layouts.app')

@section('title', 'Edit Saham')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('saham.show', $saham) }}" class="text-sm text-gray-500 hover:text-[#7C1F33] flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Detail
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Saham</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $saham->kode_saham }} &middot; {{ $saham->nama_perusahaan }}</p>
    </div>

    <form action="{{ route('saham.update', $saham) }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        @include('saham._form')

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('saham.show', $saham) }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-[#7C1F33] hover:bg-[#69182A] text-white transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
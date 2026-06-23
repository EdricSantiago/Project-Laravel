@extends('layouts.app')

@section('title', 'Tambah Saham')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <div class="flex items-center gap-4 mb-3">
            <a href="{{ route('saham.index') }}" class="text-sm text-gray-500 hover:text-[#7C1F33] flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Saham
            </a>
            <a href="{{ route('homepage') }}" class="text-sm text-gray-500 hover:text-[#7C1F33] flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Homepage
            </a>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Saham Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Saham ini akan langsung tampil dan bisa di-invest oleh semua user</p>
    </div>

    <form action="{{ route('saham.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
        @csrf

        @include('saham._form')

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('saham.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg text-gray-500 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-[#7C1F33] hover:bg-[#69182A] text-white transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
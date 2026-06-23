@extends('layouts.app')

@section('title', 'Edit Pinjaman - Bank Untar')

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
                    <a href="{{ route('pinjaman.show', $pinjaman) }}" class="hover:text-bank-red transition">Detail</a>
                    <i class="material-icons text-xs">chevron_right</i>
                    <span class="text-gray-600">Edit</span>
                </div>

                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Edit Pinjaman</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Perbarui detail pengajuan pinjaman Anda.</p>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <i class="material-icons text-sm mt-0.5">error_outline</i>
                        {{ $error }}
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-bank-border">
                        <h2 class="text-sm font-bold text-gray-700">Edit Pengajuan</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('pinjaman.update', $pinjaman) }}" method="POST" class="space-y-5">
                            @csrf @method('PUT')

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Jumlah Pinjaman</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                                    <input type="number" name="amount" value="{{ old('amount', $pinjaman->amount) }}"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-bank-red transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tenor</label>
                                <select name="tenor_months"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-bank-red transition bg-white">
                                    @foreach([3, 6, 12, 24, 36] as $tenor)
                                    <option value="{{ $tenor }}" {{ old('tenor_months', $pinjaman->tenor_months) == $tenor ? 'selected' : '' }}>
                                        {{ $tenor }} Bulan
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tujuan Pinjaman</label>
                                <input type="text" name="purpose" value="{{ old('purpose', $pinjaman->purpose) }}"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-bank-red transition">
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit"
                                    class="flex-1 bg-bank-red hover:bg-red-800 text-white py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('pinjaman.show', $pinjaman) }}"
                                    class="flex-1 text-center border border-gray-200 text-gray-600 hover:bg-gray-50 py-2.5 rounded-xl text-sm transition">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
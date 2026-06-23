@extends('layouts.app')

@section('title', 'Pinjaman - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">Pinjaman</h1>
                        <p class="text-sm text-gray-400 mt-0.5">Kelola pengajuan pinjaman Anda.</p>
                    </div>
                    <a href="{{ route('pinjaman.create') }}"
                        class="inline-flex items-center gap-2 bg-bank-red hover:bg-red-800 text-white text-sm px-4 py-2 rounded-xl font-semibold transition shadow-sm">
                        <i class="material-icons text-base">add</i>
                        Ajukan Pinjaman
                    </a>
                </div>

                {{-- Table Card --}}
                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-bank-border">
                        <h2 class="text-sm font-bold text-gray-700">Daftar Pinjaman</h2>
                    </div>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide border-b border-bank-border">
                                <th class="px-6 py-3 text-left font-medium">#</th>
                                <th class="px-6 py-3 text-left font-medium">Tujuan</th>
                                <th class="px-6 py-3 text-left font-medium">Jumlah</th>
                                <th class="px-6 py-3 text-left font-medium">Tenor</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pinjaman as $item)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-gray-800 font-semibold">{{ $item->purpose }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->formatted_amount }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $item->tenor_months }} bln</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $item->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $item->status === 'pending'  ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $item->status === 'rejected' ? 'bg-red-100 text-red-600' : '' }}
                                        {{ $item->status === 'lunas'    ? 'bg-blue-100 text-blue-700' : '' }}
                                    ">{{ $item->status_label }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pinjaman.show', $item) }}"
                                            class="text-xs text-bank-red border border-red-200 hover:bg-red-50 px-3 py-1 rounded-lg transition">Detail</a>
                                        @if($item->status === 'pending')
                                        <a href="{{ route('pinjaman.edit', $item) }}"
                                            class="text-xs text-gray-600 border border-gray-200 hover:bg-gray-50 px-3 py-1 rounded-lg transition">Edit</a>
                                        <form action="{{ route('pinjaman.destroy', $item) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin hapus pinjaman ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-red-500 border border-red-200 hover:bg-red-50 px-3 py-1 rounded-lg transition">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="material-icons text-gray-200 text-5xl">account_balance_wallet</i>
                                        <p class="text-sm text-gray-300 font-medium">Belum ada pinjaman.</p>
                                        <a href="{{ route('pinjaman.create') }}" class="text-sm text-bank-red hover:underline font-semibold">Ajukan sekarang</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($pinjaman->hasPages())
                    <div class="px-6 py-4 border-t border-bank-border">
                        {{ $pinjaman->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
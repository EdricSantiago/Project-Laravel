@extends('layouts.app')

@section('title', 'Portofolio Saya')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Portofolio Saham Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat investasi saham yang sudah Anda beli</p>
        </div>
        <a href="{{ route('saham.index') }}"
            class="inline-flex items-center gap-2 bg-[#7C1F33] hover:bg-[#69182A] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Invest Saham Baru
        </a>
    </div>

    @if (session('success'))
    <div class="bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg border border-green-100">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg border border-red-100">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($investasi->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z" />
            </svg>
            <p class="text-sm">Anda belum punya investasi saham</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#F7F2EC] text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Perusahaan</th>
                        <th class="px-6 py-3">Lembar</th>
                        <th class="px-6 py-3">Harga Beli</th>
                        <th class="px-6 py-3">Total Modal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($investasi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->saham->kode_saham }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->saham->nama_perusahaan }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ number_format($item->jumlah_lembar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->total_investasi, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $item->status === 'aktif' ? 'bg-[#FCE9ED] text-[#7C1F33]' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('investasi.show', $item) }}" class="text-[#7C1F33] hover:underline font-medium">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @if ($investasi->hasPages())
    <div>{{ $investasi->links() }}</div>
    @endif
</div>
@endsection
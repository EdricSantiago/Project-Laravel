@extends('layouts.app')

@section('title', 'Admin — Kelola Pinjaman - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-6xl mx-auto space-y-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">Kelola Pinjaman</h1>
                        <p class="text-sm text-gray-400 mt-0.5">Semua ajuan pinjaman dari nasabah.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.pinjaman.index') }}" class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-bank-red transition">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('status') === 'approved'  ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </form>
                </div>

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
                @endif
                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-bank-border">
                        <h2 class="text-sm font-bold text-gray-700">Daftar Ajuan</h2>
                    </div>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide border-b border-bank-border">
                                <th class="px-6 py-3 text-left font-medium">#</th>
                                <th class="px-6 py-3 text-left font-medium">Nasabah</th>
                                <th class="px-6 py-3 text-left font-medium">Tujuan</th>
                                <th class="px-6 py-3 text-left font-medium">Jumlah</th>
                                <th class="px-6 py-3 text-left font-medium">Tenor</th>
                                <th class="px-6 py-3 text-left font-medium">Tanggal</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pinjaman as $item)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $item->account->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->account->account_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->purpose }}</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">{{ $item->formatted_amount }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $item->tenor_months }} bln</td>
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                    $badge = match($item->status) {
                                    'approved' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'rejected' => 'bg-red-100 text-red-600',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-gray-100 text-gray-500',
                                    };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pinjaman.show', $item) }}"
                                        class="text-xs text-bank-red border border-red-200 hover:bg-red-50 px-3 py-1.5 rounded-lg transition font-medium">
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="material-icons text-gray-200 text-5xl">inbox</i>
                                        <p class="text-sm text-gray-300 font-medium">Belum ada ajuan pinjaman.</p>
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
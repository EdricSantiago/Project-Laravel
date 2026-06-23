@extends('layouts.app')

@section('title', 'Admin - Konfirmasi Top Up')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                <h1 class="text-2xl font-extrabold text-gray-900">Konfirmasi Top Up</h1>

                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl">{{ session('success') }}</div>
                @endif

                <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Invoice</th>
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Tujuan</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $order->invoice_number }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->product->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->destination_number }}</td>
                                    <td class="px-4 py-3 font-bold text-gray-900">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <form action="{{ route('ecommerce.admin.approve', $order->id) }}" method="POST">
                                            @csrf
                                            <button class="text-emerald-600 text-xs border border-emerald-400 px-2 py-1 rounded hover:bg-emerald-50">Approve</button>
                                        </form>
                                        <form action="{{ route('ecommerce.admin.reject', $order->id) }}" method="POST">
                                            @csrf
                                            <button class="text-red-600 text-xs border border-red-400 px-2 py-1 rounded hover:bg-red-50">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">Tidak ada transaksi pending.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Transaksi Berhasil')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-md mx-auto text-center">
                <div class="bg-white rounded-2xl border border-bank-border p-10">
                    <i class="material-icons text-amber-500 text-6xl mb-4">hourglass_top</i>
                    <h1 class="text-xl font-extrabold text-gray-900 mb-1">Menunggu Konfirmasi</h1>
                    <p class="text-sm text-gray-400 mb-6">{{ $order->invoice_number }}</p>

                    <div class="text-left space-y-3 bg-gray-50 rounded-xl p-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Produk</span>
                            <span class="font-bold text-gray-900">{{ $order->product->name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Tujuan</span>
                            <span class="font-bold text-gray-900">{{ $order->destination_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Total</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('ecommerce.index') }}"
                        class="block mt-6 bg-bank-red text-white py-3 rounded-xl font-bold text-sm hover:bg-red-900 transition-all">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
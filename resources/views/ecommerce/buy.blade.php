@extends('layouts.app')

@section('title', 'Beli ' . $product->name)

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl border border-bank-border p-8">
                    <h1 class="text-xl font-extrabold text-gray-900 mb-1">{{ $product->name }}</h1>
                    <p class="text-sm text-gray-400 mb-6">{{ $product->provider }}</p>

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-bank-red text-sm rounded-xl p-3 mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('ecommerce.process', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-gray-500">
                                {{ $product->category === 'pulsa' ? 'Nomor HP Tujuan' : 'ID Meter / No. Pelanggan' }}
                            </label>
                            <input type="text" name="destination_number" required
                                class="w-full mt-1 border border-bank-border rounded-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:border-bank-red"
                                placeholder="Masukkan nomor">
                        </div>

                        <div class="flex justify-between items-center bg-gray-50 rounded-xl px-4 py-3">
                            <span class="text-sm text-gray-500">Total Bayar</span>
                            <span class="text-lg font-extrabold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit"
                            class="w-full bg-bank-red text-white py-3 rounded-xl font-bold text-sm hover:bg-red-900 transition-all">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
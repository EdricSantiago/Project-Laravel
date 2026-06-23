@extends('layouts.app')

@section('title', 'E-Commerce - Bank Untar')

@section('content')
<div class="flex h-screen bg-bank-bg">
    @include('partials.sidebar')
    <div class="flex-1 flex flex-col overflow-hidden">
        @include('partials.topnav')
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                <h1 class="text-2xl font-extrabold text-gray-900">Top Up & Pembayaran</h1>

                <div class="flex gap-2">
                    <a href="{{ route('ecommerce.index', ['category' => 'pulsa']) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold {{ $category === 'pulsa' ? 'bg-bank-red text-white' : 'bg-white text-gray-500 border border-bank-border' }}">
                        Pulsa
                    </a>
                    <a href="{{ route('ecommerce.index', ['category' => 'token_listrik']) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold {{ $category === 'token_listrik' ? 'bg-bank-red text-white' : 'bg-white text-gray-500 border border-bank-border' }}">
                        Token Listrik
                    </a>
                    <a href="{{ route('ecommerce.index', ['category' => 'air']) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold {{ $category === 'air' ? 'bg-bank-red text-white' : 'bg-white text-gray-500 border border-bank-border' }}">
                        Air
                    </a>
                </div>

                @if($products->isEmpty())
                    <div class="bg-white rounded-2xl border border-bank-border p-10 text-center text-gray-400">
                        Belum ada produk untuk kategori ini.
                    </div>
                @endif

                @foreach($products as $provider => $items)
                    <div class="bg-white rounded-2xl border border-bank-border p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $provider }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($items as $item)
                                <a href="{{ route('ecommerce.buy', $item->id) }}"
                                    class="border border-bank-border rounded-xl p-4 hover:border-bank-red hover:bg-red-50/40 transition-all">
                                    <p class="text-sm font-bold text-gray-900">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </main>
    </div>
</div>
@endsection
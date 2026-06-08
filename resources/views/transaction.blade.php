<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi — Digital Banking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Digital Banking</h1>
    <div class="flex gap-3 items-center">
        <a href="{{ route('dashboard') }}" class="text-sm hover:underline">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-white text-blue-700 px-4 py-1.5 rounded-lg text-sm font-medium">
                Keluar
            </button>
        </form>
    </div>
</nav>

<div class="max-w-4xl mx-auto mt-10 px-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Info Saldo --}}
    <div class="bg-blue-600 text-white rounded-2xl p-6 mb-6">
        <p class="text-sm opacity-80">Saldo Rekening</p>
        <p class="text-3xl font-bold mt-1">
            Rp {{ number_format($account?->balance ?? 0, 0, ',', '.') }}
        </p>
        <p class="text-sm opacity-70 mt-1">No. Rekening: {{ $account?->account_number ?? '-' }}</p>
    </div>

    {{-- Aksi Transaksi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        {{-- Deposit --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Setor (Deposit)</h3>
            <form method="POST" action="{{ route('transaction.deposit') }}">
                @csrf
                <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="10000" required>
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                    Setor
                </button>
            </form>
        </div>

        {{-- Withdraw --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Tarik (Withdraw)</h3>
            <form method="POST" action="{{ route('transaction.withdraw') }}">
                @csrf
                <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="10000" required>
                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                    Tarik
                </button>
            </form>
        </div>

        {{-- Transfer --}}
        <div class="bg-white rounded-2xl shadow p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Transfer</h3>
            <form method="POST" action="{{ route('transaction.transfer') }}">
                @csrf
                <input type="text" name="receiver_account" placeholder="No. Rekening Tujuan"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <input type="number" name="amount" placeholder="Jumlah (min Rp 10.000)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    min="10000" required>
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg text-sm transition">
                    Transfer
                </button>
            </form>
        </div>

    </div>

    <div class="p-6 bg-white rounded-lg shadow-md mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Pembayaran Asuransi</h3>
            <p class="text-sm text-gray-600 mb-4">Tagihan Bulanan: <span class="font-bold text-red-500">Rp 100.000</span></p>
    
            <form action="{{ route('transaction.payInsurance') }}" method="POST" 
      onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Memproses...';">
    @csrf
    <button type="submit" class="...">Bayar Sekarang</button>
</form>
    </div>

    {{-- Riwayat Transaksi --}}
    <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-700">Riwayat Transaksi</h3>
        <a href="{{ route('transaction.exportPdf') }}"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Export PDF
        </a>
    </div>
        @forelse($transactions as $trx)
            <div class="flex justify-between items-center border-b py-3 text-sm">
                <div>
                    <span class="font-medium capitalize">{{ $trx->type }}</span>
                    <p class="text-gray-400 text-xs">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="{{ $trx->type === 'deposit' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        {{ $trx->type === 'deposit' ? '+' : '-' }}
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-gray-400 capitalize">{{ $trx->status }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm text-center py-4">Belum ada riwayat transaksi.</p>
        @endforelse
    </div>

</div>
</body>
</html>

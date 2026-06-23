<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-red-700 text-white px-6 py-4 flex items-center justify-between shadow">
        <span class="font-bold text-lg tracking-wide">Bank Untar</span>
        <div class="flex items-center gap-6 text-sm">
            <a href="{{ url('/homepage') }}" class="hover:underline opacity-90">Homepage</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="border border-white px-3 py-1 rounded hover:bg-white hover:text-red-700 transition text-sm font-medium">
                    Keluar
                </button>
            </form>
        </div>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <div class="max-w-2xl mx-auto py-8 px-4 space-y-5">

        <a href="{{ route('admin.pinjaman.index') }}"
            class="text-sm text-gray-500 hover:text-red-700 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Pinjaman
        </a>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 text-sm px-4 py-3 rounded border border-green-200">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded shadow p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Data Nasabah</p>
            <p class="font-semibold text-gray-800 text-base">{{ $pinjaman->account->user->name ?? '-' }}</p>
            <p class="text-sm text-gray-500">No. Rekening: {{ $pinjaman->account->account_number ?? '-' }}</p>
        </div>

        <div class="bg-white rounded shadow overflow-hidden">
            <div class="bg-red-700 px-5 py-3">
                <p class="text-white text-xs uppercase tracking-wide font-medium">Detail Ajuan Pinjaman #{{ $pinjaman->id }}</p>
            </div>
            <table class="w-full text-sm">
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium w-2/5">Tujuan</th>
                    <td class="px-5 py-3 text-gray-800">{{ $pinjaman->purpose }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Jumlah</th>
                    <td class="px-5 py-3 text-gray-800 font-semibold">{{ $pinjaman->formatted_amount }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Tenor</th>
                    <td class="px-5 py-3 text-gray-800">{{ $pinjaman->tenor_months }} bulan</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Bunga</th>
                    <td class="px-5 py-3 text-gray-800">{{ $pinjaman->interest_rate }}% / bulan</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Cicilan / Bulan</th>
                    <td class="px-5 py-3 text-red-700 font-semibold">{{ $pinjaman->formatted_monthly_installment }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Total Bayar</th>
                    <td class="px-5 py-3 text-gray-800 font-semibold">{{ $pinjaman->formatted_total_repayment }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Tanggal Ajuan</th>
                    <td class="px-5 py-3 text-gray-800">{{ $pinjaman->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Status</th>
                    <td class="px-5 py-3">
                        @php
                        $badge = match($pinjaman->status) {
                        'approved' => 'bg-green-100 text-green-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'rejected' => 'bg-red-100 text-red-600',
                        'completed' => 'bg-blue-100 text-blue-700',
                        default => 'bg-gray-100 text-gray-500',
                        };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">
                            {{ $pinjaman->status_label }}
                        </span>
                    </td>
                </tr>

                @if ($pinjaman->status === 'approved' && $pinjaman->approved_at)
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Disetujui Pada</th>
                    <td class="px-5 py-3 text-green-700">{{ \Carbon\Carbon::parse($pinjaman->approved_at)->format('d M Y, H:i') }}</td>
                </tr>
                @endif

                @if ($pinjaman->status === 'rejected')
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-gray-500 font-medium">Ditolak Pada</th>
                    <td class="px-5 py-3 text-red-600">{{ \Carbon\Carbon::parse($pinjaman->rejected_at)->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <th class="px-5 py-3 text-left text-gray-500 font-medium align-top">Alasan Penolakan</th>
                    <td class="px-5 py-3 text-red-600">{{ $pinjaman->rejection_reason }}</td>
                </tr>
                @endif
            </table>
        </div>

        @if ($pinjaman->status === 'pending')
        <div class="bg-white rounded shadow p-5 space-y-3">
            <p class="text-sm font-semibold text-gray-700">Keputusan Admin</p>
            <div class="flex gap-3">
                {{-- Tombol Setujui --}}
                <form action="{{ route('admin.pinjaman.approve', $pinjaman) }}" method="POST"
                    onsubmit="return confirm('Setujui pinjaman ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="bg-red-700 hover:bg-red-800 text-white text-sm font-medium px-5 py-2 rounded transition">
                        ✓ Setujui
                    </button>
                </form>

                {{-- Tombol Tolak --}}
                <button type="button" onclick="document.getElementById('modal-tolak').classList.remove('hidden')"
                    class="border border-red-400 text-red-600 hover:bg-red-50 text-sm font-medium px-5 py-2 rounded transition">
                    ✕ Tolak
                </button>
            </div>
        </div>
        @endif

    </div>

    <div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded shadow w-full max-w-md p-6 space-y-4">
            <h2 class="text-base font-bold text-gray-800">Alasan Penolakan</h2>
            <p class="text-sm text-gray-500">Alasan ini akan dicatat dan bisa dilihat oleh nasabah.</p>

            @error('rejection_reason')
            <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror

            <form action="{{ route('admin.pinjaman.reject', $pinjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                <textarea name="rejection_reason" rows="3" required
                    placeholder="cth. Riwayat kredit tidak memenuhi syarat..."
                    class="w-full text-sm rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 mb-4">{{ old('rejection_reason') }}</textarea>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-tolak').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded transition">
                        Tolak Pinjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($errors->has('rejection_reason'))
    <script>
        document.getElementById('modal-tolak').classList.remove('hidden')
    </script>
    @endif

</body>

</html>
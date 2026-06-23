<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-red-700 text-white px-6 py-4 flex justify-between items-center shadow">
        <span class="font-bold text-lg tracking-wide">Bank Untar</span>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('homepage') }}" class="hover:underline opacity-90">Homepage</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="border border-white px-3 py-1 rounded hover:bg-white hover:text-red-700 transition font-medium">Keluar</a>
        </div>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

    <div class="max-w-lg mx-auto py-8 px-4">

        <h5 class="text-xl font-semibold text-gray-800 mb-6">Detail Pinjaman</h5>

        <div class="bg-white rounded shadow overflow-hidden mb-4">
            <div class="bg-red-700 px-4 py-3">
                <p class="text-white text-xs uppercase tracking-wide font-medium">Informasi Pinjaman</p>
            </div>
            <table class="w-full text-sm">
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 w-1/3 font-medium">Tujuan</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->purpose }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Jumlah</th>
                    <td class="px-4 py-3 text-gray-800 font-semibold">{{ $pinjaman->formatted_amount }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Tenor</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->tenor_months }} bulan</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Bunga</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->interest_rate }}% / bulan</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Cicilan/Bulan</th>
                    <td class="px-4 py-3 text-red-700 font-semibold">{{ $pinjaman->formatted_monthly_installment }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Total Bayar</th>
                    <td class="px-4 py-3 text-gray-800 font-semibold">{{ $pinjaman->formatted_total_repayment }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium
                        {{ $pinjaman->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $pinjaman->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $pinjaman->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $pinjaman->status === 'lunas' ? 'bg-blue-100 text-blue-700' : '' }}
                    ">{{ $pinjaman->status_label }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Tanggal</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->created_at->format('d M Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('pinjaman.index') }}" class="border border-gray-400 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded text-sm transition">← Kembali</a>
            @if($pinjaman->status === 'pending')
            <a href="{{ route('pinjaman.edit', $pinjaman) }}" class="border border-red-700 text-red-700 hover:bg-red-50 px-4 py-2 rounded text-sm transition">Edit</a>
            <form action="{{ route('pinjaman.destroy', $pinjaman) }}" method="POST" class="inline"
                onsubmit="return confirm('Yakin hapus?')">
                @csrf @method('DELETE')
                <button class="border border-red-400 text-red-600 hover:bg-red-50 px-4 py-2 rounded text-sm transition">Hapus</button>
            </form>
            @endif
        </div>

    </div>
</body>

</html>
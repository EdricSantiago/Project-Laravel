<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-lg mx-auto py-8 px-4">

        <h5 class="text-xl font-semibold text-gray-800 mb-6">Detail Pinjaman</h5>

        <div class="bg-white rounded shadow overflow-hidden mb-4">
            <table class="w-full text-sm">
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600 w-1/3">Tujuan</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->purpose }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Jumlah</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->formatted_amount }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Tenor</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->tenor_months }} bulan</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Bunga</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->interest_rate }}% / bulan</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Cicilan/Bulan</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->formatted_monthly_installment }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Total Bayar</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->formatted_total_repayment }}</td>
                </tr>
                <tr class="border-b">
                    <th class="px-4 py-3 text-left text-gray-600">Status</th>
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
                    <th class="px-4 py-3 text-left text-gray-600">Tanggal</th>
                    <td class="px-4 py-3 text-gray-800">{{ $pinjaman->created_at->format('d M Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('pinjaman.index') }}" class="border border-gray-400 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded text-sm">← Kembali</a>
            @if($pinjaman->status === 'pending')
            <a href="{{ route('pinjaman.edit', $pinjaman) }}" class="border border-blue-500 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded text-sm">Edit</a>
            <form action="{{ route('pinjaman.destroy', $pinjaman) }}" method="POST" class="inline"
                onsubmit="return confirm('Yakin hapus?')">
                @csrf @method('DELETE')
                <button class="border border-red-400 text-red-600 hover:bg-red-50 px-4 py-2 rounded text-sm">Hapus</button>
            </form>
            @endif
        </div>

    </div>
</body>

</html>
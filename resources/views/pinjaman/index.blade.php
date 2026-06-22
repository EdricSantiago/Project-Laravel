<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <nav class="bg-red-700 text-white px-6 py-4 flex justify-between items-center shadow">
        <span class="font-bold text-lg tracking-wide">Bank Untar</span>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('homepage') }}" class="hover:underline opacity-90">Homepage</a>
            <a href="{{ route('homepage') }}" class="border border-white px-3 py-1 rounded hover:bg-white hover:text-red-700 transition font-medium">Keluar</a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto py-8 px-4">

        <div class="flex justify-between items-center mb-6">
            <h5 class="text-xl font-semibold text-gray-800">Pinjaman</h5>
            <a href="{{ route('pinjaman.create') }}" class="bg-red-700 hover:bg-red-800 text-white text-sm px-4 py-2 rounded font-medium transition">+ Ajukan</a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-red-700 text-white text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Tujuan</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Tenor</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pinjaman as $item)
                    <tr class="hover:bg-red-50 transition">
                        <td class="px-4 py-3 text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->purpose }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->formatted_amount }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->tenor_months }} bln</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $item->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $item->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $item->status === 'lunas' ? 'bg-blue-100 text-blue-700' : '' }}
                        ">{{ $item->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('pinjaman.show', $item) }}" class="text-red-700 hover:underline text-xs border border-red-700 px-2 py-1 rounded">Detail</a>
                            @if($item->status === 'pending')
                            <a href="{{ route('pinjaman.edit', $item) }}" class="text-gray-600 hover:underline text-xs border border-gray-400 px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('pinjaman.destroy', $item) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-xs border border-red-400 px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada pinjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pinjaman->links() }}
        </div>

    </div>
</body>

</html>
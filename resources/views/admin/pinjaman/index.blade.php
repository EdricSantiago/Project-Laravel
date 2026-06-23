<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Kelola Pinjaman</title>
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

    <div class="max-w-6xl mx-auto py-8 px-4 space-y-5">

        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Kelola Pinjaman</h1>
                <p class="text-sm text-gray-500 mt-0.5">Semua ajuan pinjaman dari nasabah</p>
            </div>

            {{-- Filter status --}}
            <form method="GET" action="{{ route('admin.pinjaman.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-red-700 focus:border-red-700">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending'    ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved'   ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected'   ? 'selected' : '' }}>Ditolak</option>
                    <option value="completed" {{ request('status') === 'completed'  ? 'selected' : '' }}>Lunas</option>
                </select>
            </form>
        </div>

        @if (session('success'))
        <div class="bg-green-100 text-green-800 text-sm px-4 py-3 rounded border border-green-200">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-red-700 text-white text-xs uppercase">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nasabah</th>
                        <th class="px-4 py-3">Tujuan</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Tenor</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pinjaman as $item)
                    <tr class="hover:bg-red-50 transition">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $item->account->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->account->account_number ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->purpose }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->formatted_amount }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->tenor_months }} bln</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @php
                            $badge = match($item->status) {
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'rejected' => 'bg-red-100 text-red-600',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default => 'bg-gray-100 text-gray-500',
                            };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $badge }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pinjaman.show', $item) }}"
                                class="text-red-700 border border-red-700 hover:bg-red-50 text-xs px-3 py-1.5 rounded transition">
                                Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">
                            Belum ada ajuan pinjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $pinjaman->links() }}</div>

    </div>
</body>

</html>
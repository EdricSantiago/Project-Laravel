<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pinjaman</title>
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

        <h5 class="text-xl font-semibold text-gray-800 mb-6">Edit Pinjaman</h5>

        @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <div class="bg-red-700 px-4 py-3">
                <p class="text-white text-xs uppercase tracking-wide font-medium">Edit Pengajuan</p>
            </div>
            <div class="p-6">
                <form action="{{ route('pinjaman.update', $pinjaman) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="amount" value="{{ old('amount', $pinjaman->amount) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tenor</label>
                        <select name="tenor_months" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            @foreach([3, 6, 12, 24, 36] as $tenor)
                            <option value="{{ $tenor }}" {{ old('tenor_months', $pinjaman->tenor_months) == $tenor ? 'selected' : '' }}>
                                {{ $tenor }} Bulan
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                        <input type="text" name="purpose" value="{{ old('purpose', $pinjaman->purpose) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>

                    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white py-2 rounded text-sm font-medium transition">Simpan</button>
                    <a href="{{ route('pinjaman.show', $pinjaman) }}" class="block text-center w-full mt-2 border border-gray-400 text-gray-600 hover:bg-gray-50 py-2 rounded text-sm transition">Batal</a>
                </form>
            </div>
        </div>

    </div>
</body>

</html>
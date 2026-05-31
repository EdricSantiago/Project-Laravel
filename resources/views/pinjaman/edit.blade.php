<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-lg mx-auto py-8 px-4">

        <h5 class="text-xl font-semibold text-gray-800 mb-6">Edit Pinjaman</h5>

        @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <div class="bg-white rounded shadow p-6">
            <form action="{{ route('pinjaman.update', $loan) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="amount" value="{{ old('amount', $loan->amount) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenor</label>
                    <select name="tenor_months" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach([3, 6, 12, 24, 36] as $tenor)
                        <option value="{{ $tenor }}" {{ old('tenor_months', $loan->tenor_months) == $tenor ? 'selected' : '' }}>
                            {{ $tenor }} Bulan
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                    <input type="text" name="purpose" value="{{ old('purpose', $loan->purpose) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm font-medium">Simpan</button>
                <a href="{{ route('pinjaman.show', $loan) }}" class="block text-center w-full mt-2 border border-gray-400 text-gray-600 hover:bg-gray-50 py-2 rounded text-sm">Batal</a>
            </form>
        </div>

    </div>
</body>

</html>
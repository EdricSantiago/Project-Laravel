<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Digital Banking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Digital Banking</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-white text-blue-700 px-4 py-1.5 rounded-lg text-sm font-medium">
            Keluar
        </button>
    </form>
</nav>

<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <p class="text-gray-500 text-sm">Selamat datang,</p>
        <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-600 text-white rounded-2xl p-6">
            <p class="text-sm opacity-80">Nomor Rekening</p>
            <p class="text-xl font-bold mt-1">{{ Auth::user()->account_number }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-semibold text-gray-800 mt-1">{{ Auth::user()->email }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-gray-500">No. HP</p>
            <p class="font-semibold text-gray-800 mt-1">{{ Auth::user()->no_hp }}</p>
        </div>
    </div>
</div>

</body>
</html>
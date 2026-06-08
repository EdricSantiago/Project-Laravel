<header class="h-16 bg-white border-b border-bank-border flex items-center justify-between px-6 shrink-0 z-10">
    <!-- Left: Logo + Nav Links -->
    <div class="flex items-center gap-10">
        <h1 class="text-2xl font-black text-bank-red tracking-tight">Bank Untar</h1>
        <nav class="hidden md:flex items-center gap-6">
            <a href="#" class="text-sm text-gray-600 hover:text-bank-red transition-colors font-medium">Contact Us</a>
            <a href="#" class="text-sm text-gray-600 hover:text-bank-red transition-colors font-medium">Locations</a>
            <a href="#" class="text-sm text-gray-600 hover:text-bank-red transition-colors font-medium">Help</a>
        </nav>
    </div>

    <!-- Right: Icons + Logout + Avatar -->
    <div class="flex items-center gap-4">
        <button class="relative p-2 text-gray-400 hover:text-bank-red transition-colors">
            <i class="material-icons text-[22px]">notifications_none</i>
        </button>
        <button class="p-2 text-gray-400 hover:text-bank-red transition-colors">
            <i class="material-icons text-[22px]">settings</i>
        </button>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 text-bank-red font-bold text-sm hover:text-red-900 transition-colors ml-2">
                Logout <i class="material-icons text-lg">logout</i>
            </button>
        </form>
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-bank-red to-red-400 text-white flex items-center justify-center font-bold text-sm shadow-md ml-1 overflow-hidden">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>

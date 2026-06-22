<aside class="w-56 bg-white border-r border-bank-border flex flex-col shrink-0">
    <!-- Welcome Section -->
    <div class="px-5 pt-6 pb-4">
        <p class="text-xs text-gray-400 font-medium">Welcome back,</p>
        <h2 class="text-lg font-extrabold text-bank-red mt-0.5 uppercase tracking-tight">
            {{ Auth::user()->name ?? 'User' }}</h2>
        <span
            class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2.5 py-1 rounded-full mt-2 border border-emerald-200">
            <i class="material-icons text-[12px]">verified</i> Verified Member
        </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-2 space-y-0.5">
        @php
            $currentRoute = request()->route()?->getName();
        @endphp

        <a href="{{ route('homepage') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-all
                {{ $currentRoute === 'homepage' ? 'text-bank-red bg-red-50/70 border-l-[3px] border-bank-red' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <i class="material-icons text-[20px]">grid_view</i> Home
        </a>
        <a href="{{ route('accounts') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-all
                {{ $currentRoute === 'accounts' ? 'text-bank-red bg-red-50/70 border-l-[3px] border-bank-red' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <i class="material-icons-outlined text-[20px]">account_balance_wallet</i> Accounts
        </a>
        <a href="{{ route('transaction.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-all
                {{ $currentRoute === 'transaction.index' ? 'text-bank-red bg-red-50/70 border-l-[3px] border-bank-red' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <i class="material-icons-outlined text-[20px]">swap_horiz</i> Transfer
        </a>
        <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-all text-gray-500 hover:bg-gray-50 hover:text-gray-700">
            <i class="material-icons-outlined text-[20px]">storefront</i> E-Commerce
        </a>
        <a href="{{ route('security') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition-all
                {{ $currentRoute === 'security' ? 'text-bank-red bg-red-50/70 border-l-[3px] border-bank-red' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
            <i class="material-icons-outlined text-[20px]">security</i> Security Center
        </a>
    </nav>

    <!-- Bottom Section -->
    <div class="px-4 pb-4 mt-auto space-y-4">
        <div class="flex items-center justify-center gap-5 pt-2 pb-1">
            <a href="#"
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="material-icons-outlined text-[18px]">gavel</i>
                <span class="text-[9px] font-semibold uppercase tracking-wider">Privacy Policy</span>
            </a>
            <a href="#"
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="material-icons-outlined text-[18px]">description</i>
                <span class="text-[9px] font-semibold uppercase tracking-wider">Terms of Service</span>
            </a>
        </div>
    </div>
</aside>

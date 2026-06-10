@extends('layouts.app')

@section('title', 'Accounts - Bank Untar')

@section('content')
    <div class="flex h-screen bg-bank-bg">
        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Navigation --}}
            @include('partials.topnav')

            {{-- Scrollable Content Area --}}
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto space-y-6">

                    {{-- Page Header --}}
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Accounts Overview</h1>
                            <p class="text-sm text-gray-400 font-medium mt-1">Manage your balances and recent activities.</p>
                        </div>
                    </div>

                    {{-- Account Summary Card --}}
                    @if($account)
                        <div class="relative overflow-hidden bg-white rounded-2xl p-8 border border-bank-border group">
                            {{-- Decorative Abstract Background --}}
                            <div class="absolute top-0 right-0 w-64 h-64 bg-red-100 rounded-full blur-3xl opacity-30 -mr-20 -mt-20 pointer-events-none transition-opacity group-hover:opacity-50"></div>

                            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                                <div class="flex-1 w-full lg:w-auto">
                                    <div class="flex justify-between lg:justify-start lg:gap-8 items-start mb-6">
                                        <div>
                                            <h2 class="text-lg font-extrabold text-gray-900">Untar Savings Silver</h2>
                                            <p class="text-sm font-mono text-gray-400 tracking-wider mt-0.5">{{ $account->account_number }}</p>
                                        </div>
                                        @if($account->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> {{ ucfirst($account->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-sm text-gray-400 font-medium mb-2">Total Balance</p>
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-2xl font-bold text-gray-900">Rp</span>
                                            <span class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ number_format($account->balance, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 w-full lg:w-auto">
                                    <a href="{{ route('transaction.index') }}"
                                       class="flex-1 lg:flex-none px-8 bg-bank-red text-white py-3 rounded-xl font-bold text-sm hover:bg-red-900 transition-all text-center shadow-lg shadow-red-200/40 active:scale-[0.98]">
                                        Send Money
                                    </a>
                                    <a href="{{ route('homepage') }}"
                                       class="flex-1 lg:flex-none px-8 bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all text-center border border-bank-border">
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl border border-bank-border p-8 text-center">
                            <i class="material-icons text-gray-200 text-5xl mb-3">account_balance_wallet</i>
                            <p class="text-sm text-gray-400 font-medium">Belum ada akun terdaftar.</p>
                        </div>
                    @endif

                    {{-- Transaction History (Mutasi Rekening) --}}
                    <div class="bg-white rounded-2xl border border-bank-border flex flex-col">
                        <div class="px-6 py-5 border-b border-bank-border flex justify-between items-center">
                            <h2 class="text-lg font-extrabold text-gray-900">Mutasi Rekening</h2>
                            <a class="text-sm text-bank-red hover:underline flex items-center gap-1 font-semibold" href="{{ route('transaction.history') }}">
                                View All <i class="material-icons text-sm">arrow_forward</i>
                            </a>
                        </div>

                        <div class="flex-1 p-2">
                            @if($transactions->isEmpty())
                                <div class="p-8 text-center">
                                    <i class="material-icons text-gray-200 text-5xl mb-3">receipt_long</i>
                                    <p class="text-sm text-gray-300 font-medium">Belum ada transaksi.</p>
                                </div>
                            @else
                                <ul class="flex flex-col gap-1">
                                    @foreach($transactions as $tx)
                                        @php
                                            $isSender = $account && $tx->sender_id === $account->id;
                                            $isReceiver = $account && $tx->receiver_id === $account->id;
                                            $isIncoming = $isReceiver && !$isSender;
                                            $isOutgoing = $isSender && !$isReceiver;

                                            // Determine icon & colors based on type
                                            if ($tx->type === 'deposit') {
                                                $icon = 'arrow_downward';
                                                $bgColor = 'bg-emerald-50';
                                                $iconColor = 'text-emerald-600';
                                                $label = 'Deposit';
                                                $detail = 'Cash Deposit';
                                            } elseif ($tx->type === 'withdraw') {
                                                $icon = 'arrow_upward';
                                                $bgColor = 'bg-red-50';
                                                $iconColor = 'text-red-500';
                                                $label = 'Withdrawal';
                                                $detail = 'Cash Withdrawal';
                                            } elseif ($tx->type === 'transfer' && $isIncoming) {
                                                $icon = 'arrow_downward';
                                                $bgColor = 'bg-emerald-50';
                                                $iconColor = 'text-emerald-600';
                                                $senderAccount = $tx->sender;
                                                $senderUser = $senderAccount ? $senderAccount->user : null;
                                                $label = 'Transfer Masuk' . ($senderUser ? ' - ' . strtoupper($senderUser->name) : '');
                                                $detail = $senderAccount ? $senderAccount->account_number : 'Transfer';
                                            } elseif ($tx->type === 'transfer' && $isOutgoing) {
                                                $icon = 'arrow_upward';
                                                $bgColor = 'bg-gray-100';
                                                $iconColor = 'text-gray-500';
                                                $receiverAccount = $tx->receiver;
                                                $receiverUser = $receiverAccount ? $receiverAccount->user : null;
                                                $label = 'Transfer Keluar' . ($receiverUser ? ' - ' . strtoupper($receiverUser->name) : '');
                                                $detail = $receiverAccount ? $receiverAccount->account_number : 'Transfer';
                                            } else {
                                                $icon = 'receipt';
                                                $bgColor = 'bg-gray-100';
                                                $iconColor = 'text-gray-500';
                                                $label = ucfirst($tx->type);
                                                $detail = '-';
                                            }

                                            // Check for electricity token
                                            if ($tx->token_electricity) {
                                                $icon = 'bolt';
                                                $bgColor = 'bg-amber-50';
                                                $iconColor = 'text-amber-600';
                                                $label = 'Token Listrik';
                                                $detail = 'Token: ' . $tx->token_electricity;
                                            }

                                            // Amount display
                                            $isPositive = ($tx->type === 'deposit') || ($tx->type === 'transfer' && $isIncoming);
                                            $amountPrefix = $isPositive ? '+ ' : '- ';
                                            $amountColor = $isPositive ? 'text-emerald-600' : 'text-gray-900';
                                            $formattedAmount = $amountPrefix . 'Rp ' . number_format($tx->amount, 2, ',', '.');

                                            // Date display
                                            $txDate = $tx->created_at;
                                            if ($txDate->isToday()) {
                                                $dateLabel = 'Today, ' . $txDate->format('H:i');
                                            } elseif ($txDate->isYesterday()) {
                                                $dateLabel = 'Yesterday';
                                            } else {
                                                $dateLabel = $txDate->format('M d, Y');
                                            }
                                        @endphp

                                        <li class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50/80 transition-colors group cursor-pointer">
                                            <div class="flex items-center gap-4">
                                                {{-- Icon --}}
                                                <div class="w-12 h-12 rounded-full {{ $bgColor }} flex items-center justify-center {{ $iconColor }} group-hover:scale-110 transition-transform">
                                                    <i class="material-icons">{{ $icon }}</i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ $label }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $detail }}</p>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-base font-extrabold {{ $amountColor }}">{{ $formattedAmount }}</p>
                                                <p class="text-[11px] font-mono text-gray-400 mt-1">{{ $dateLabel }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection

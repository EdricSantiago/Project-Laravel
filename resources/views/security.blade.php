@extends('layouts.app')

@section('title', 'Security Center - Bank Untar')

@section('content')
    <div class="flex h-screen bg-bank-bg">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            @include('partials.topnav')

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-5xl mx-auto">

                    <!-- Security Center Header -->
                    <div class="bg-white rounded-2xl border border-bank-border overflow-hidden">
                        <div class="bg-gray-50/50 px-8 py-4 border-b border-bank-border flex items-center gap-3">
                            <i class="material-icons text-bank-red text-xl">security</i>
                            <h2 class="text-lg font-bold text-gray-900">Security Center</h2>
                        </div>

                        <div class="p-12 flex flex-col lg:flex-row gap-12">
                            <!-- Update PIN Section -->
                            <div class="flex-1 space-y-8">
                                <div>
                                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Update PIN</h3>
                                    <p class="text-gray-500 leading-relaxed max-w-md">
                                        Ensure your account stays secure by regularly updating your transaction PIN. Keep it
                                        unique and hard to guess.
                                    </p>
                                </div>

                                <form class="space-y-4 max-w-md">
                                    <div>
                                        <input type="password" placeholder="Old PIN (6 Digits)"
                                            class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                    </div>
                                    <div>
                                        <input type="password" placeholder="New PIN (6 Digits)"
                                            class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-bank-red outline-none transition-all placeholder:text-gray-400">
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-bank-red text-white py-4 rounded-xl font-bold hover:bg-red-900 transition-all active:scale-[0.98] shadow-lg shadow-red-100">
                                        Perbarui PIN
                                    </button>
                                </form>
                            </div>

                            <!-- Vertical Divider -->
                            <div class="hidden lg:block w-[1px] bg-gray-100"></div>

                            <!-- Emergency Action Section -->
                            <div class="flex-1">
                                <div
                                    class="bg-red-50/30 border border-red-100 rounded-3xl p-10 h-full flex flex-col items-center text-center">
                                    <div
                                        class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bank-red mb-8">
                                        <i class="material-icons text-3xl">report_problem</i>
                                    </div>

                                    <h3 class="text-2xl font-bold text-bank-red mb-6">Emergency Action</h3>

                                    <p class="text-gray-600 mb-10 leading-relaxed">
                                        Instantly block all transactions and freeze your account access if you suspect any
                                        fraudulent activity or have lost your device. This action is immediate and will log
                                        you out of all active sessions.
                                    </p>

                                    <button
                                        class="mt-auto w-full max-w-xs bg-bank-red text-white py-5 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-red-900 transition-all active:scale-95 shadow-xl shadow-red-200">
                                        <i class="material-icons">ac_unit</i> FREEZE ACCOUNT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
@endsection

<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SahamController;
use App\Http\Controllers\InvestasiController;
use App\Http\Controllers\EcommerceProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/homepage', [AccountsController::class, 'index'])->name('homepage');
    Route::get('/accounts', [AccountsController::class, 'accounts'])->name('accounts');
    Route::get('/security', [AccountsController::class, 'security'])->name('security');

    Route::resource('/pinjaman', PinjamanController::class);
    Route::resource('saham', SahamController::class);

    Route::post('saham/{saham}/invest', [InvestasiController::class, 'store'])->name('investasi.store');
    Route::get('investasi', [InvestasiController::class, 'index'])->name('investasi.index');
    Route::get('investasi/{investasi}', [InvestasiController::class, 'show'])->name('investasi.show');

    Route::get('/account',          [AccountsController::class, 'show'])->name('account.show');
    Route::post('/account/topup',   [AccountsController::class, 'topup'])->name('account.topup');
    Route::post('/account/withdraw',[AccountsController::class, 'withdraw'])->name('account.withdraw');

    Route::get('/transaction',              [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/deposit',     [TransactionController::class, 'deposit'])->name('transaction.deposit');
    Route::post('/transaction/withdraw',    [TransactionController::class, 'withdraw'])->name('transaction.withdraw')
        ->middleware('pin.cooldown');
    Route::post('/transaction/transfer',    [TransactionController::class, 'transfer'])->name('transaction.transfer')
        ->middleware('pin.cooldown');
    Route::get('/transaction/history',      [TransactionController::class, 'history'])->name('transaction.history');
    Route::post('/transaction/pay-insurance',[TransactionController::class, 'payInsurance'])->name('transaction.payInsurance')
        ->middleware('pin.cooldown');
    Route::get('/transaction/export-pdf',   [TransactionController::class, 'exportPdf'])->name('transaction.exportPdf');

    Route::prefix('security')->group(function () {
    Route::post('/setup-pin',       [SecurityController::class, 'setupPin'])->name('security.setup');
    Route::post('/panic',           [SecurityController::class, 'freezeAccount'])->name('security.panic');
    Route::post('/change-pin',      [SecurityController::class, 'changePin'])->name('security.change');
    Route::post('/verify-pin',      [SecurityController::class, 'verifyPin'])->name('security.verify');
    Route::get('/status',           [SecurityController::class, 'getSecurityStatus'])->name('security.status');
    Route::post('/change-password', [SecurityController::class, 'changePassword'])->name('security.password');
    });

    Route::get('/settings/security-log', [SettingsController::class, 'securityLog'])->name('settings.security-log');

    Route::prefix('ecommerce')->name('ecommerce.')->group(function () {
    Route::get('/', [EcommerceProductController::class, 'index'])->name('index');
    Route::get('/buy/{product}', [EcommerceProductController::class, 'buy'])->name('buy');
    Route::post('/buy/{product}', [EcommerceProductController::class, 'process'])->name('process');
    Route::get('/success/{order}', [EcommerceProductController::class, 'success'])->name('success');
    Route::get('/history', [EcommerceProductController::class, 'history'])->name('history');
    });

});
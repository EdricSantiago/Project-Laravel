<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/homepage', [AccountsController::class, 'index'])->name('dashboard');

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
    Route::post('/transaction/pay-insurance',[TransactionController::class, 'payInsurance'])->name('transaction.payInsurance');
    Route::get('/transaction/export-pdf',   [TransactionController::class, 'exportPdf'])->name('transaction.exportPdf');

    Route::prefix('security')->group(function () {
        Route::post('/setup-pin',   [SecurityController::class, 'setupPin'])->name('security.setup');
        Route::post('/panic',       [SecurityController::class, 'freezeAccount'])->name('security.panic');
        Route::post('/change-pin',  [SecurityController::class, 'changePin'])->name('security.change');
        Route::post('/verify-pin',  [SecurityController::class, 'verifyPin'])->name('security.verify');
        Route::get('/status',       [SecurityController::class, 'getSecurityStatus'])->name('security.status');
        Route::post('/security/change-password', [SecurityController::class, 'changePassword'])->name('security.password');
        Route::get('/settings/security-log', [SettingsController::class, 'securityLog'])->name('settings.security-log');
    });
});

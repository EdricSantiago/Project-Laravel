<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
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

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/deposit', [TransactionController::class, 'deposit'])->name('transaction.deposit');
    Route::post('/transaction/withdraw', [TransactionController::class, 'withdraw'])->name('transaction.withdraw');
    Route::post('/transaction/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');
    Route::get('/transaction/history', [TransactionController::class, 'history'])->name('transaction.history');
});
<?php

namespace App\Http\Controllers;

use App\Models\EcommerceOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EcommerceAdminController extends Controller
{
    public function index()
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        $orders = EcommerceOrder::with('product', 'user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('ecommerce.admin', compact('orders'));
    }

    public function approve(EcommerceOrder $order)
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        DB::transaction(function () use ($order) {
            $account = $order->user->account;

            if ($account->balance < $order->price) {
                abort(422, 'Saldo user tidak cukup, transaksi otomatis gagal.');
            }

            $account->balance -= $order->price;
            $account->save();

            $order->status = 'success';
            $order->save();

            $order->transaction?->update(['status' => 'success']);
        });

        return back()->with('success', 'Transaksi disetujui, saldo telah dipotong.');
    }

    public function reject(EcommerceOrder $order)
    {
        abort_if(!Auth::user()->isAdmin(), 403);

        $order->status = 'failed';
        $order->save();

        $order->transaction?->update(['status' => 'failed']);

        return back()->with('success', 'Transaksi ditolak.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\EcommerceOrder;
use App\Models\EcommerceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EcommerceProductController extends Controller
{
    // Tampilkan katalog produk, bisa difilter per kategori
    public function index(Request $request)
    {
        $category = $request->get('category', 'pulsa');

        $products = EcommerceProduct::where('status', true)
            ->where('category', $category)
            ->orderBy('price')
            ->get()
            ->groupBy('provider');

        return view('ecommerce.index', compact('products', 'category'));
    }

    // Tampilkan form beli untuk 1 produk
    public function buy(EcommerceProduct $product)
    {
        return view('ecommerce.buy', compact('product'));
    }

    // Proses pembelian
    public function process(Request $request, EcommerceProduct $product)
    {
        $request->validate([
            'destination_number' => 'required|string|min:5|max:20',
        ]);

        $user = Auth::user();
        $account = $user->account;

        // Karena status awalnya pending (belum dikonfirmasi admin),
        // saldo BELUM dipotong di sini -- baru dipotong saat admin approve.
        if (!$account || $account->balance < $product->price) {
            return back()->withErrors(['balance' => 'Saldo Anda tidak cukup untuk transaksi ini.']);
        }

        $order = EcommerceOrder::create([
            'user_id' => $user->id,
            'ecommerce_product_id' => $product->id,
            'invoice_number' => 'ECM-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'destination_number' => $request->destination_number,
            'price' => $product->price,
            'status' => 'pending',
        ]);

        return redirect()->route('ecommerce.success', $order->id)
            ->with('message', 'Transaksi berhasil dibuat, menunggu konfirmasi admin.');
    }

    public function success(EcommerceOrder $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('product');

        return view('ecommerce.success', compact('order'));
    }

    public function history()
    {
        $orders = EcommerceOrder::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ecommerce.history', compact('orders'));
    }
}
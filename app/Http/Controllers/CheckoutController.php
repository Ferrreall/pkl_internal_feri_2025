<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\CartService; // Tambahkan ini
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;

    // Inject CartService agar sinkron dengan CartController
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        // Ambil data dari service, bukan langsung dari user relasi
        $cart = $this->cartService->getCart();
        
        // Eager load untuk memastikan items terbaca
        $cart->load('items.product');

        // Cek apakah beneran kosong
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        return view('checkout.index', compact('cart'));
    }

   public function store(Request $request, OrderService $orderService)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
    ]);

    try {
        // Pastikan kita refresh user agar relasinya tidak "nyangkut"
        $user = auth()->user();
        
        $order = $orderService->createOrder($user, $request->only(['name', 'phone', 'address']));

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silahkan lakukan pembayaran.');
    } catch (\Exception $e) {
        // Alert merah "Keranjang belanja kosong" tidak akan muncul lagi kalau datanya terbaca
        return back()->with('error', $e->getMessage());
    }
}
}
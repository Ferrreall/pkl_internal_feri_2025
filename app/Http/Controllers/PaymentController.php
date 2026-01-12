<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getSnapToken(Order $order, MidtransService $midtransService)
    {
        // 1. Validasi User
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // 2. Minta Token Segar
            $snapToken = $midtransService->createSnapToken($order);

            // 3. Update snap_token di database agar tersimpan
            $order->update([
                'snap_token' => $snapToken
            ]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
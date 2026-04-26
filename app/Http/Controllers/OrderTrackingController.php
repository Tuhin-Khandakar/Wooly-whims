<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return view('track.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->with('items')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check your order number.');
        }

        return view('track.status', compact('order'));
    }
}

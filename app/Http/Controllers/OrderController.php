<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items')->paginate(request()->get('per_page', 15), ['*'], 'page', request()->get('page', 1));
        return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'delivery_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $lastNumber = Order::all()->sortBy('timestamp')?->last()->order_identifier_number ?? 0;
        $number = 1;
        if($lastNumber != 100){
            $number = $lastNumber + 1;
        }

        $order = Order::create([
            'user_id' => $data['user_id'],
            'order_identifier_number' => $number,
            'status' => $data['status'],
            'delivery_date' => $data['delivery_date'],
        ]);

        foreach ($data['items'] as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
            ]);
        }

        return response()->json(['message' => 'Rendelés sikeresen leadva', 'order' => $order, 'completion_time' => $order->completionTime()], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

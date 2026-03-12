<?php

namespace App\Http\Controllers;

use App\Events\NewOrderSubmitted;
use App\Events\OrderStateChanged;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            OrderResource::collection(Order::all()->where(fn($item) => Gate::allows('view', $item))),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ez átkerült a payment controllerbe, mivel ott van a checkout folyamat, és ott van értelme létrehozni a rendelést
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::all()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Rendelés nem található'], 404);
        }
        $order->load('items');
        return response()->json(new OrderResource($order), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::all()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Rendelés nem található'], 404);
        }

        $data = $request->validate([
            'status_id' => 'sometimes|exists:statuses,id',
            'delivery_date' => 'sometimes|date',
        ]);

        $order->update($data);
        broadcast(new OrderStateChanged($order));
        return response()->json(['message' => 'Rendelés sikeresen frissítve', 'order' => new OrderResource($order)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::all()->find($id);
        if (!$order) {
            return response()->json(['message' => 'Rendelés nem található'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Rendelés sikeresen törölve'], 200);
    }
}

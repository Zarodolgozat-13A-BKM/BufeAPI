<?php

namespace App\Http\Controllers;

use App\Events\NewOrderSubmitted;
use App\Events\OrderStateChanged;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Status;
use App\Services\JedlikCsengoService;
use App\Services\ReceiptManagementService;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $orders = Order::paginate(15)->where(fn($item) => Gate::allows('view', $item));
        // $orders = Order::paginate(15)->where(fn($item) => Gate::allows('view', $item));
        // $orders = Order::all()->where(fn($item) => Gate::allows('view', $item))->paginate(15);
        $orders = Order::query()
            ->where(fn($query) => $query->where(fn($item) => Gate::allows('view', $item)))
            ->paginate(10);
        return OrderResource::collection($orders);
    }

    public function getActiveOrders()
    {
        return response()->json(
            OrderResource::collection(Order::whereNotIn('status_id', [Status::where('name', 'Átadva')->first()->id, Status::where('name', 'Törölve')->first()->id])->get()->where(fn($item) => Gate::allows('view', $item))),
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
    public function show(Order $order)
    {
        return response()->json(new OrderResource($order), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

        $data = $request->validate([
            'status_id' => 'sometimes|exists:statuses,id',
            'delivery_date' => 'sometimes|datetime|after:datetime:now|before:datetime:now+1 day',
        ]);

        $order->update($data);
        return response()->json(['message' => 'Rendelés sikeresen frissítve', 'order' => new OrderResource($order)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Rendelés sikeresen törölve'], 200);
    }

    public function getBreaks($date = null)
    {
        $jedlikCsengoService = new JedlikCsengoService();
        return response()->json(['date' => $date ?? date('Y-m-d'), 'breaks' => $jedlikCsengoService->getRingTableForDate($date ?? date('Y-m-d'))], 200);
    }


    public function orderIsReady(Order $order)
    {
        $order->update(['status_id' => Status::where('name', 'Átvehető')->first()->id]);
        return response()->json(['message' => 'Rendelés státusza Átvehető-re változtatva'], 200);
    }

    public function orderIsTaken(Order $order)
    {
        $order->update(['status_id' => Status::where('name', 'Átadva')->first()->id]);
        return response()->json(['message' => 'Rendelés státusza Átadva-re változtatva'], 200);
    }
}

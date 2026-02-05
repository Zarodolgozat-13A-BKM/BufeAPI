<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\ItemPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(ItemPolicy::class)]
class ItemController extends Controller
{
    //
    public function index(Request $request, User $user)
    {
        return response()->json([
            'items' => Item::all()->where(fn($item) => Gate::allows('view', $item))
        ], 200);
    }
    public function show(Request $request, $id, User $user)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Nincs ilyen termék'], 404);
        }
        return response()->json(['item' => $item], 200);
    }
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'picture_url' => 'nullable|url',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'default_time_to_deliver' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'sometimes|boolean',
        ]);
        $item = Item::create($data);
        return response()->json(['message' => 'Termék sikeresen létrehozva', 'item' => $item], 201);
    }
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Nincs ilyen termék'], 404);
        }
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'picture_url' => 'sometimes|nullable|url',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
            'default_time_to_deliver' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_featured' => 'sometimes|boolean',
        ]);
        $item->update($data);
        return response()->json(['message' => 'Termék sikeresen frissítve', 'item' => $item], 200);
    }
    public function delete(Request $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Nincs ilyen termék'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'Termék sikeresen törölve'], 200);
    }
    public function toggleItemActiveStatus(Request $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Nincs ilyen termék'], 404);
        }
        $item->is_active = !$item->is_active;
        $item->save();
        return response()->json(['message' => 'Termék státusza sikeresen frissítve', 'item' => $item], 200);
    }
}

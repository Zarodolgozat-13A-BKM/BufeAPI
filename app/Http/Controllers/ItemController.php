<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\ItemPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    //
    public function index(Request $request, User $user)
    {
        return response()->json([
            'items' => Item::all()->where(fn($item) => Gate::allows('view', $item))
        ], 200);
    }
    public function show(Request $request, Item $item, User $user)
    {
        return response()->json(['item' => $item], 200);
    }
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'sometimes|file',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'default_time_to_deliver' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'sometimes|boolean',
            'inventory_count' => 'sometimes|integer|min:0',
        ]);
        $url = "placeholder.jpg";
        if ($request->hasFile('image')) {
            $url = $request->file('image')->store('itemImages', 'public');
        }
        $data['picture_url'] = $url;
        $item = Item::create($data);
        return response()->json(new ItemResource($item), 201);
    }
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|nullable|file',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'is_active' => 'sometimes|required|boolean',
            'default_time_to_deliver' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_featured' => 'sometimes|boolean',
            'inventory_count' => 'sometimes|integer|min:0',
        ]);
        $url = null;
        if ($request->hasFile('image')) {
            $url = $request->file('image')->store('itemImages', 'public');
        }
        $data['picture_url'] = $url;
        // $item = Item::create($data);
        // $item->update($data);
        $item->update($data);
        return response()->json(new ItemResource($item), 200);
    }
    public function destroy(Request $request, Item $item)
    {
        if (Storage::disk('public')->exists($item->picture_url)) {
            Storage::disk('public')->delete($item->picture_url);
        }
        $item->delete();
        return response()->json(['message' => 'Termék sikeresen törölve'], 200);
    }
    public function toggleItemActiveStatus(Request $request, Item $item)
    {
        $item->toggleActive();
        return response()->json(['message' => 'Termék státusza sikeresen frissítve', 'item' => ItemResource::make($item)], 200);
    }

    public function toggleItemFeaturedStatus(Request $request, Item $item)
    {
        $item->toggleFeatured();
        return response()->json(['message' => 'Termék státusza sikeresen frissítve', 'item' => ItemResource::make($item)], 200);
    }
}

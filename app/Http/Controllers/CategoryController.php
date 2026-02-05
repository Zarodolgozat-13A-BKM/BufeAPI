<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(CategoryPolicy::class)]
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('items')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($data);
        return response()->json(['message' => 'Kategória sikeresen létrehozva', 'category' => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('items')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Nincs ilyen kategória'], 404);
        }
        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::with('items')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Nincs ilyen kategória'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);
        return response()->json(['message' => 'Kategória sikeresen frissítve', 'category' => $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::with('items')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Nincs ilyen kategória'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Kategória sikeresen törölve'], 200);
    }
}

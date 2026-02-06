<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Http\Request;
use App\Policies\StatusPolicy;

#[UsePolicy(StatusPolicy::class)]
class StatusController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Status::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = Status::create($validatedData);

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return $status;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status->update($validatedData);

        return response()->json($status, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Native;
use Illuminate\Http\Request;

class NativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Native::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:text, image',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048',
            'url' => 'nullable|url',
        ]);

        // if img
        if ($request->hasFile('image_path')) {
            $fileName = $request->file('image_path')->hashName();
            $request->file('image_path')->storeAs('natives', $fileName, 'public');
            $validated['image_path'] = $fileName;
        }

        $native = Native::create($validated);

        return response()->json($native, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Native $native)
    {
        return $native;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Native $native)
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|in:text, image',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048',
            'url' => 'nullable|url',
        ]);

        if ($request->hasFile('image_path')) {
            $fileName = $request->file('image_path')->hashName();
            $request->file('image_path')->storeAs('natives', $fileName, 'public');
            $validated['image_path'] = $fileName;

        }

        $native->update($validated);

        return response()->json($native);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Native $native)
    {
        $native->delete();

        return response()->json(null, 204);
    }
}

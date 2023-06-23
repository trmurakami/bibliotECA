<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Http\Controllers\WorkController;

class WorksAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $works = Work::all();
        return response()->json($works);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $work = Work::create($request->all());
        WorkController::indexRelations($work->id);
        return response()->json($work, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json($work);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $work = Work::findOrFail($id);
        $updateWork = $work->update($request->all());
        return response()->json($updateWork);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Work::delete();
        return response()->json(null, 204);
    }
}
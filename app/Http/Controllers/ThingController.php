<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thing;

class ThingController extends Controller
{
    public function index(Request $request)
    {
        $query = Thing::query();
        if ($request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        $things = $query->withCount('works')->with('works')
        ->orderByDesc('works_count')->orderByRaw('name COLLATE NOCASE')->paginate(15);

        return view('things.index', compact('things'));
    }

    public function create()
    {
        return view('things.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Thing::create($validatedData);

        return redirect()->route('things.index')->with('success', 'Thing created successfully.');
    }

    public function edit($id)
    {
        $thing = Thing::findOrFail($id);

        return view('things.edit', compact('thing'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $thing = Thing::findOrFail($id);
        $thing->update($validatedData);

        return redirect()->route('things.index')->with('success', 'Thing updated successfully.');
    }

    public function destroy($id)
    {
        $thing = Thing::findOrFail($id);
        $thing->delete();

        return redirect()->route('things.index')->with('success', 'Thing deleted successfully.');
    }
}
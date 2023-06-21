<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::withCount('works')->with('works')
        ->orderByDesc('works_count')->get();

        return view('people.index', compact('people'));
    }

    public function create()
    {
        return view('people.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Person::create($validatedData);

        return redirect()->route('people.index')->with('success', 'Person created successfully.');
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);

        return view('people.edit', compact('person'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $person = Person::findOrFail($id);
        $person->update($validatedData);

        return redirect()->route('people.index')->with('success', 'Person updated successfully.');
    }

    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return redirect()->route('people.index')->with('success', 'Person deleted successfully.');
    }
}
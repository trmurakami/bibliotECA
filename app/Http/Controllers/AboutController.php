<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = About::query();
        if ($request->name) {
            $query->where('name', 'iLIKE', '%' . $request->name . '%');
        }
        $abouts = $query->withCount('works')->with('works')
        ->orderByDesc('works_count')->paginate(15);

        return view('abouts.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('abouts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        About::create($validatedData);

        return redirect()->route('abouts.index')->with('success', 'About created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(About $about)
    {
        $about = About::findOrFail($id);
        return view('abouts.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $about = About::findOrFail($id);
        $about->update($validatedData);

        return redirect()->route('abouts.index')->with('success', 'About updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        $about = About::findOrFail($id);
        $about->delete();

        return redirect()->route('abouts.index')->with('success', 'About deleted successfully.');
    }
}
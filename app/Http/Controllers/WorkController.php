<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (!$request->per_page) {
            $request->per_page = 20;
        }

        $query = Work::query();

        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }


        $works = $query->orderByDesc('id')->paginate($request->per_page);

        $facets[0]['field'] = 'type';
        $facets[0]['fieldName'] = 'Tipo';
        $facets[0]['values'] = self::facet('type')->toArray();
        $facets[1]['field'] = 'name';
        $facets[1]['fieldName'] = 'Título';
        $facets[1]['values'] = self::facet('name')->toArray();

        return view('works.index', compact('works', 'request', 'facets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('works.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description'  => 'required',
        ]);

        Work::create($request->all());

        return redirect()->route('works.index')
            ->with('success', 'Work created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        return view('works.show', compact('work'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        return view('works.edit', compact('work'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        $request->validate([
            'type' => 'required',
            'name' => 'required',
            'description'  => 'required',
        ]);

        $work->update($request->all());

        return redirect()->route('works.index')
            ->with('success', 'Work updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        $work->delete();

        return redirect()->route('works.index')
            ->with('success', 'Work deleted successfully');
    }

    public static function facet(String $facet)
    {
        $query = Work::select('' . $facet . ' as field', DB::raw('count(*) as count'));
        $query->groupBy($facet)->orderByDesc('count')->orderByDesc($facet)->limit(10);        
        $facets = $query->get();
        return $facets;
    }
}
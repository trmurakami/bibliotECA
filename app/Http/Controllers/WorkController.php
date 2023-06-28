<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (!$request->per_page) {
            $request->per_page = 10;
        }

        $query = Work::query()->with('authors');

        if ($request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->datePublished) {
            $query->where('datePublished', $request->datePublished);
        }

        if ($request->author) {
            $search = $request->author;
            $query->whereHas('authors', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->releasedEvent) {
            $query->where('releasedEvent', $request->releasedEvent);
        }

        if ($request->isPartOf_name) {
            $query->where('isPartOf_name', $request->isPartOf_name);
        }

        if ($request->inLanguage) {
            $query->where('inLanguage', 'like', '%' .  $request->inLanguage . '%');
        }

        if ($request->issn) {
            $query->where('issn', $request->issn);
        }

        $works = $query->orderByDesc('datePublished')->paginate($request->per_page)->withQueryString();

        return view('works.index', compact('works', 'request'));
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
            'type' => 'required',
            'name' => 'required'
        ]);

        $id = Work::create($request->all())->id;

        self::indexRelations($id);

        return redirect()->route('works.index')
            ->with('success', 'Work created successfully.');
    }

    public static function storeFromCSV($data)
    {
        $work = new Work();
        $work->type = $data['type'];
        $work->name = $data['name'];
        $work->datePublished = $data['datePublished'];
        $saved = $work->save();
        if (!$saved) {
            Log::error('Erro ao salvar o trabalho: ' . $data['name']);
        }
        return $saved;
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

    public static function indexRelations($id)
    {
        $record = Work::find($id);
        $record->authors()->detach();
        if ($record->author) {
            $personsAttached = [];
            foreach ($record->author as $author) {
                if ($author["id"] != "") {
                    $person = Person::find($author["id"]);
                    $personsAttached[] = $person->id;
                    $record->authors()->attach($person, ['function' => $author['function']]);
                } else {
                    $person = Person::where('name', $author["name"])->first();
                    if (!$person) {
                        $person = new Person();
                        $person->name = $author["name"];
                        $person->save();
                    }
                    $personsAttached[] = $person->id;
                    if (!in_array($person->id, $personsAttached)) {
                        dd($personsAttached);
                        $record->authors()->attach($person, ['function' => $author['function']]);
                    }
                }
            }
            unset($personsAttached);
        }
        if ($record->about) {
            foreach ($record->about as $about) {
                if ($about["id"] != "") {
                    $person = Thing::find($about["id"]);
                    $record->authors()->attach($person, ['function' => "about"]);
                }
            }
        }
        if ($record->director) {
            foreach ($record->director as $director) {
                if ($director["id"] != "") {
                    $person = Person::find($director["id"]);
                    $record->authors()->attach($person, ['function' => "director"]);
                }
            }
        }
        if ($record->actor) {
            foreach ($record->actor as $actor) {
                if ($actor["id"] != "") {
                    $person = Thing::find($actor["id"]);
                    $record->authors()->attach($person, ['function' => "actor"]);
                }
            }
        }
        if ($record->musicby) {
            foreach ($record->musicby as $musicby) {
                if ($musicby["id"] != "") {
                    $person = Thing::find($musicby["id"]);
                    $record->authors()->attach($person, ['function' => "musicby"]);
                }
            }
        }
        if ($record->productionCompany) {
            foreach ($record->productionCompany as $productionCompany) {
                if ($productionCompany["id"] != "") {
                    $person = Thing::find($productionCompany["id"]);
                    $record->authors()->attach($person, ['function' => "productionCompany"]);
                }
            }
        }
        if ($record->translator) {
            foreach ($record->translator as $translator) {
                if ($translator["id"] != "") {
                    $person = Thing::find($translator["id"]);
                    $record->authors()->attach($person, ['function' => "translator"]);
                }
            }
        }
        // if ($record->publisher) {
        //     foreach ($record->publisher as $publisher) {
        //         if ($publisher["id"] != "") {
        //             $person = Thing::find($publisher["id"]);
        //             $record->authors()->attach($person, ['function' => "publisher"]);
        //         }
        //     }
        // }
    }
    public function graficos()
    {
        $datePublishedData = DB::table('works')
                        ->select('datePublished as year', \DB::raw('COUNT(*) as total'))
                        ->groupBy('year')
                        ->get();
        return view('works.graficos', compact('datePublishedData'));
    }
}
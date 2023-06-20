<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Person;

class WorkPersonController extends Controller
{
    public function attachPerson(Request $request, $workId)
    {
        $work = Work::findOrFail($workId);
        $personIds = $request->input('person_ids', []);

        $work->authors()->attach($personIds);

        return redirect()->route('works.show', $workId)->with('success', 'Authors attached successfully.');
    }

    public function detachPerson($workId, $personId)
    {
        $work = Work::findOrFail($workId);
        $work->authors()->detach($personId);

        return redirect()->route('works.show', $workId)->with('success', 'Author detached successfully.');
    }
}
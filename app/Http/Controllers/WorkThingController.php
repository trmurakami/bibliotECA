<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Thing;

class WorkThingController extends Controller
{
    public function attachThing(Request $request, $workId)
    {
        $work = Work::findOrFail($workId);
        $thingIds = $request->input('thing_ids', []);

        $work->authors()->attach($thingIds);

        return redirect()->route('works.show', $workId)->with('success', 'Authors attached successfully.');
    }

    public function detachThing($workId, $thingId)
    {
        $work = Work::findOrFail($workId);
        $work->authors()->detach($thingId);

        return redirect()->route('works.show', $workId)->with('success', 'Author detached successfully.');
    }
}
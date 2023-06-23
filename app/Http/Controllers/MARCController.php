<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Scriptotek\Marc\Collection;
use App\Http\Controllers\WorkController;
use App\Models\Work;

class MARCController extends Controller
{
    public function processMARC(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xml,mrc|max:2048',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $newFilename = $request->input('new_filename');
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $newFilename);
            if ($file->getClientOriginalExtension() === 'xml') {
                $curriculo = simplexml_load_file($request->file);
                $collection = Collection::fromSimpleXMLElement($curriculo);
                foreach ($collection as $record) {
                    echo $record->getField('245')->getSubfield('a')->getData() . "\n";
                }
            } elseif ($file->getClientOriginalExtension() === 'mrc') {
                $collection = Collection::fromFile($request->file);
                foreach ($collection as $record) {
                    //echo $record->getField('245')->getSubfield('a')->getData() . "<br/>";
                    $workArray['type'] = 'Livro';
                    $workArray['name'] = (string)$record->getField('245')->getSubfield('a')->getData();
                    $work = new Work($workArray);
                    $work->save();
                    WorkController::indexRelations($work->id);
                    unset($record);
                }
            }
        }
    }

}
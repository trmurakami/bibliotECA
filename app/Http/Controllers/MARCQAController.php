<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;

class MARCQAController extends Controller
{
    public function marcQA(Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:2048',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            if ($file->getClientOriginalExtension() === 'mrc') {
                $collection = Collection::fromFile($request->file);
                $result = [];
                $result['count']['title'] = 0;
                $result['count']['subtitle'] = 0;
                $result['count']['author'] = 0;
                $result['count']['publisher'] = 0;
                foreach ($collection as $record) {
                    //dd($record->title);
                    if (null !== $record->getField('245')->getSubfield('a')) {
                        $result['title'][] =  $record->getField('245')->getSubfield('a')->getData();
                        $result['count']['title']++;
                    }
                    if (null !== $record->query('245$b')->text()) {
                        $result['subtitle'][] =  $record->query('245$b')->text();
                        $result['count']['subtitle']++;
                    }
                    if (null !== $record->getField('100')) {
                        $result['author'][] =  $record->getField('100')->getSubfield('a')->getData();
                        $result['count']['author']++;
                    }
                    if (null !== $record->getField('260')->getSubfield('b')) {
                        $result['publisher'][] =  $record->getField('260')->getSubfield('b')->getData();
                        $result['count']['publisher']++;
                    }

                    $result['count_unique']['author'] = count(array_unique($result['author']));
                    $result['count_unique']['publisher'] = count(array_unique($result['publisher']));
                }
            }
        }
        return response()->json($result, 201);
    }
    public function exportfield (Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:2048',
            'field' => 'required',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            if ($file->getClientOriginalExtension() === 'mrc') {
                $collection = Collection::fromFile($request->file);
                $result = [];
                $result['count'] = 0;
                foreach ($collection as $record) {
                    if (null !== $record->getField($request->field)) {
                        $result['field'][] =  $record->getField($request->field)->getSubfield($request->subfield)->getData();
                        $result['count']++;
                    }
                }
            }
        }
        return response()->json($result, 201);
    }
}
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
            'file' => 'required|mimes:mrc|max:102400',
        ]);
        if ($request->file('file')->isValid()) {
            
            $file = $request->file('file');
            $collection = Collection::fromFile($request->file);
            $result = [];
            $result['count'] = 0;
            foreach ($collection as $record) {
                if (isset($request->marcfield)) {
                    if (null !== $record->query($request->marcfield)->text()) {
                        foreach ($record->query($request->marcfield) as $subfield) {
                            $result['field'][] = $subfield->getData();
                            $result['count']++;
                         }
                    }
                } else {
                    return response()->json(['error' => 'Field not found'], 404);
                }
            }
            return response()->json($result, 201);
        } else {
            return response()->json(['error' => 'File not valid'], 404);
        }
    }

    public function reportTitleInd2 (Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:102400',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $collection = Collection::fromFile($request->file);
            $result = [];
            $articles = ['O', 'Os', 'A', 'As', 'Um', 'Uns', 'Uma', 'Umas', 'Ao', 'Aos', 'À', 'Às', 'Do', 'Dos', 'Da','Das',
            'Dum', 'Duns', 'Duma', 'Dumas', 'No', 'Nos', 'Na', 'Nas', 'Num', 'Nuns', 'Numa', 'Numas', 'Pelo', 'Pelos', 'Pela',
            'Pelas', 'The', 'An', 'A', 'Les', 'La', 'Le', 'L', 'El', 'Los', 'Las', 'Lo', 'Els', 'Es', 'Un', 'Una', 'Uns', 'Unes'];
            $i = 0;
            foreach ($collection as $record) {
                $result[$i]['title'] = trim($record->getField('245')->getSubfield('a')->getData());
                $result[$i]['ind2'] = $record->getField('245')->getIndicator(2);
                $firstWord = ucwords(strtolower(explode(' ', $result[$i]['title'])[0]));
                if (in_array($firstWord, $articles)) {
                    $result[$i]['ind2_suggest'] = (string)(strlen($firstWord) + 1);
                    if ($result[$i]['ind2_suggest'] == $result[$i]['ind2']) {
                        $result[$i]['ind2_needs_correct'] = false;
                    } else {
                        $result[$i]['ind2_needs_correct'] = true;
                    }
                } else {
                    $result[$i]['ind2_suggest'] = "0";
                    $result[$i]['ind2_needs_correct'] = false;
                }
                //$result[$i]['recordRaw'] = $record->toRaw();
                $i++;
            }
            if ($request->onlyErrors) {
                $result = array_filter($result, function($item) {
                    return $item['ind2_needs_correct'] == true;
                });
                return response()->json($result, 201);
            } else {
                return response()->json($result, 201);
            }
        } else {
            return response()->json(['error' => 'File not valid'], 404);
        }
    }
    public function correctTitleInd2 (Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:102400',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $collection = Collection::fromFile($request->file);
            $result = [];
            $articles = ['O', 'Os', 'A', 'As', 'Um', 'Uns', 'Uma', 'Umas', 'Ao', 'Aos', 'À', 'Às', 'Do', 'Dos', 'Da','Das',
            'Dum', 'Duns', 'Duma', 'Dumas', 'No', 'Nos', 'Na', 'Nas', 'Num', 'Nuns', 'Numa', 'Numas', 'Pelo', 'Pelos', 'Pela',
            'Pelas', 'The', 'An', 'A', 'Les', 'La', 'Le', 'L', 'El', 'Los', 'Las', 'Lo', 'Els', 'Es', 'Un', 'Una', 'Uns', 'Unes'];
            $i = 0;
            foreach ($collection as $record) {
                $result[$i]['title'] = trim($record->getField('245')->getSubfield('a')->getData());
                $result[$i]['ind2'] = $record->getField('245')->getIndicator(2);
                $firstWord = ucwords(strtolower(explode(' ', $result[$i]['title'])[0]));
                if (in_array($firstWord, $articles)) {
                    $result[$i]['ind2_suggest'] = (string)(strlen($firstWord) + 1);
                    if ($result[$i]['ind2_suggest'] != $result[$i]['ind2']) {
                        $record->getField('245')->setIndicator(2, $result[$i]['ind2_suggest']);
                    }
                }
                $resultArray[] = $record->toRaw();
                $i++;
            }
            $result = implode('', $resultArray);
            $headers = array(
                "Content-type" => "text/plain",
                "Content-Disposition" => "attachment; filename=result.mrc",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            return response( $result, 200, $headers );

        } else {
            return response()->json(['error' => 'File not valid'], 404);
        }
    }
}
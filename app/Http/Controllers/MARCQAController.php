<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;
use Scriptotek\Marc\Fields\Field;
use Scriptotek\Marc\Fields\ControlField;
use Scriptotek\Marc\Fields\DataField;
use Scriptotek\Marc\Fields\Subfield;
use File_MARC;
use File_MARC_Source_File;
use File_MARC_Record;
use File_MARC_Field;
use File_MARC_Control_Field;


class MARCQAController extends Controller
{
    public function marcQA(Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:2048',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            if ($file->getClientOriginalExtension() === 'mrc') {
                $collection = new File_MARC($request->file);
                $result['count'] = [];
                while ($record = $collection->next()) {
                    foreach ($record->getFields() as $tag=>$value) {
                        $string_tag = 'Tag - '.$tag;
                        if (isset($result['count'][$string_tag])) {
                            $result['count'][$string_tag]++;
                        } else {
                            $result['count'][$string_tag] = 1;
                        }

                        if ($value instanceof File_MARC_Control_Field) {
                   
                        } else {
                            foreach ($value->getSubfields() as $code=>$subdata) {
                                $string_code = 'Tag - '.$tag.' - Code - '.$code;
                                if (isset($result['count'][$string_code])) {
                                    $result['count'][$string_code]++;
                                } else {
                                    $result['count'][$string_code] = 1;
                                }
                            }
                        }

                    }
                }
                $array = $result['count'];

                ksort($array);

            }
        }
        return response()->json($array, 201);
    }

    public function marcQAReport(Request $request) {
        $request->validate([
            'file' => 'required|mimes:mrc|max:2048',
        ]);
        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            if ($file->getClientOriginalExtension() === 'mrc') {
                $collection = new File_MARC($request->file);
                $result['count'] = [];
                while ($record = $collection->next()) {
                    foreach ($record->getFields() as $tag=>$value) {
                        $string_tag = 'Tag - '.$tag;
                        if (isset($result['count'][$string_tag])) {
                            $result['count'][$string_tag]++;
                        } else {
                            $result['count'][$string_tag] = 1;
                        }

                        if ($value instanceof File_MARC_Control_Field) {
                   
                        } else {
                            foreach ($value->getSubfields() as $code=>$subdata) {
                                $string_code = 'Tag - '.$tag.' - Code - '.$code;
                                if (isset($result['count'][$string_code])) {
                                    $result['count'][$string_code]++;
                                } else {
                                    $result['count'][$string_code] = 1;
                                }
                            }
                        }

                    }
                }
                $tags = $result['count'];

                ksort($tags);

            }
        }
        return view('marcqa.report', compact('tags'));
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
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
                    //$workArray['subtitle'] = (string)$record->getField('245')->getSubfield('b')->getData();
                    //$workArray['edition'] = (string)$record->getField('250')->getSubfield('a')->getData();
                    $workArray['datePublished'] = str_replace(['.', '[', ']', 'c'], '', (string)$record->getField('260')->getSubfield('c')->getData());
                    //$workArray['abstract'] = (string)$record->getField('520')->getSubfield('a')->getData();
                    $workArray['isbn'] = (string)$record->getField('020')->getSubfield('a')->getData();
                    $workArray['publisher'] = (string)$record->getField('260')->getSubfield('b')->getData();
                    $i_autores = 0;
                    if (null !== $record->getField('100')){
                        $workArray['author'][$i_autores]['id'] = '';
                        $workArray['author'][$i_autores]['id_lattes13'] = '';
                        $workArray['author'][$i_autores]['name'] = (string)$record->getField('100')->getSubfield('a')->getData();
                        $workArray['author'][$i_autores]['function'] = 'Autor';
                        $i_autores++;
                    }
                    foreach ($record->getFields('700') as $autor) {
                        $i_autores++;
                        $workArray['author'][$i_autores]['id'] = '';
                        $workArray['author'][$i_autores]['id_lattes13'] = '';
                        $workArray['author'][$i_autores]['name'] = (string)$autor->getSubfield('a')->getData();
                        $workArray['author'][$i_autores]['function'] = 'Autor';
                    }
                    $work = new Work($workArray);
                    $work->save();
                    WorkController::indexRelations($work->id);
                    unset($record);
                }
                return redirect('/works')->with('success', 'Trabalhos importados com sucesso!');
            }
        }
    }

}
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
            'file' => 'required|mimes:xml,mrc|max:102400',
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
                    $workArray['name'] = $record->query('245$a')->text();
                    $workArray['datePublished'] = str_replace(['.', '[', ']', 'c'], '', $record->query('260$c')->text());
                    $workArray['isbn'] = $record->query('020$a')->text();
                    $workArray['publisher'] = $record->query('260$b')->text();
                    $i_autores = 0;
                    if (null !== $record->getField('100')){
                        if (!empty($record->query('100$a')->text())) {
                            $workArray['author'][$i_autores]['id'] = '';
                            $workArray['author'][$i_autores]['id_lattes13'] = '';
                            $workArray['author'][$i_autores]['name'] = $record->query('100$a')->text();
                            $workArray['author'][$i_autores]['function'] = 'Autor';
                            $i_autores++;
                        }
                    }
                    foreach ($record->query('700') as $field) {
                        if (!empty($record->query('700$a')->text())) {
                            $workArray['author'][$i_autores]['id'] = '';
                            $workArray['author'][$i_autores]['id_lattes13'] = '';
                            $workArray['author'][$i_autores]['name'] = $record->query('700$a')->text();
                            $workArray['author'][$i_autores]['function'] = 'Autor';
                            $i_autores++;
                        }
                    }
                    if (isset($workArray['author'])) {
                        $workArray['author'] = array_map("unserialize", array_unique(array_map("serialize", $workArray['author'])));
                    }
                    $work = new Work($workArray);
                    $work->save();
                    WorkController::indexRelations($work->id);
                    unset($record);
                    unset($workArray);
                }
                return redirect('/works')->with('success', 'Trabalhos importados com sucesso!');
            }
        }
    }

}
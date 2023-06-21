<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Person;
use App\Http\Controllers\WorkController;

class LattesController extends Controller
{
    public function processXML(Request $request)
    {
        if ($request->file) {
            $curriculo = simplexml_load_file($request->file);
            //dd($curriculo);

            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'})) { 
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'} as $trabalho) {
                    
                    $record['name'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['TITULO-DO-TRABALHO'];
                    $record['datePublished'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['ANO-DO-TRABALHO'];
                    $record['type'] = "Trabalho em Evento";
                    $record['releasedEvent'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['NOME-DO-EVENTO'];
                    $record['doi'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['DOI'];
                    $i_autores = 0;
                    foreach ($trabalho->{'AUTORES'} as $autor) {
                        $record['author'][$i_autores]['name'] = (string)$autor->attributes()->{'NOME-COMPLETO-DO-AUTOR'};
                        $record['author'][$i_autores]['id_lattes13'] = (string)$autor->attributes()->{'NRO-ID-CNPQ'};
                        $record['author'][$i_autores]['function'] = 'Autor';
                        $existingPerson = Person::where('name', $record['author'][$i_autores]['name'])->first();
                        if ($existingPerson) {
                            $record['author'][$i_autores]['id'] = $existingPerson->id;
                        } else {
                            $newPersonID = Person::firstOrCreate(['name' => $record['author'][$i_autores]['name'], 'id_lattes13' => $record['author'][$i_autores]['id_lattes13']])->id;
                            $record['author'][$i_autores]['id'] = $newPersonID;
                        }
                        $i_autores++;
                    }
                    $work = new Work($record);
                    $work->save();
                    WorkController::indexRelations($work->id);
                    unset($record);
                }
            }
            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'} as $artigo) {
                    //dd($artigo);
                    $record['name'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['TITULO-DO-ARTIGO'];
                    $record['datePublished'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['ANO-DO-ARTIGO'];
                    $record['type'] = "Artigo";
                    $record['isPartOf_name'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['TITULO-DO-PERIODICO-OU-REVISTA'];
                    $record['doi'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['DOI'];
                    $i_autores = 0;
                    foreach ($artigo->{'AUTORES'} as $autor) {
                        $record['author'][$i_autores]['name'] = (string)$autor->attributes()->{'NOME-COMPLETO-DO-AUTOR'};
                        $record['author'][$i_autores]['id_lattes13'] = (string)$autor->attributes()->{'NRO-ID-CNPQ'};
                        $record['author'][$i_autores]['function'] = 'Autor';
                        $existingPerson = Person::where('name', $record['author'][$i_autores]['name'])->first();
                        if ($existingPerson) {
                            $record['author'][$i_autores]['id'] = $existingPerson->id;
                        } else {
                            $newPersonID = Person::firstOrCreate(['name' => $record['author'][$i_autores]['name'], 'id_lattes13' => $record['author'][$i_autores]['id_lattes13']])->id;
                            $record['author'][$i_autores]['id'] = $newPersonID;
                        }
                        $i_autores++;
                    }
                    $work = new Work($record);
                    $work->save();
                    WorkController::indexRelations($work->id);
                    unset($record);
                }
            }
        }
    }
}
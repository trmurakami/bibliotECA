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
            // Create Person
            $personLattesID = Person::firstOrCreate([
                'id_lattes13' => (string)$curriculo['NUMERO-IDENTIFICADOR'],
                'name' => (string)$curriculo->{'DADOS-GERAIS'}['NOME-COMPLETO']
            ])->id;

            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'})) { 
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'} as $trabalho) {
                    $record['name'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['TITULO-DO-TRABALHO'];
                    $record['datePublished'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['ANO-DO-TRABALHO'];
                    $record['type'] = "Trabalho em Evento";
                    $record['releasedEvent'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['NOME-DO-EVENTO'];
                    $record['doi'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['DOI'];
                    $record['inLanguage'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['IDIOMA'];
                    $record['issn'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['ISSN'];
                    $record['volumeNumber'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['VOLUME'];
                    $record['issueNumber'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['FASCICULO'];
                    $record['pageStart'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['PAGINA-INICIAL'];
                    $record['pageEnd'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['PAGINA-FINAL'];
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
                    $record['name'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['TITULO-DO-ARTIGO'];
                    $record['datePublished'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['ANO-DO-ARTIGO'];
                    $record['type'] = "Artigo";
                    $record['isPartOf_name'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['TITULO-DO-PERIODICO-OU-REVISTA'];
                    $record['doi'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['DOI'];
                    $record['inLanguage'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['IDIOMA'];
                    $record['issn'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['ISSN'];
                    $record['volumeNumber'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['VOLUME'];
                    $record['issueNumber'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['FASCICULO'];
                    $record['pageStart'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['PAGINA-INICIAL'];
                    $record['pageEnd'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['PAGINA-FINAL'];
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
            return redirect('/works')->with('success', 'Trabalhos importados com sucesso!');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Thing;
use App\Models\Qualis;
use App\Http\Controllers\WorkController;

class LattesController extends Controller
{

    public function createThing($curriculo, Request $request)
    {
        $curriculo = get_object_vars($curriculo);
        $curriculo_dados_gerais = get_object_vars($curriculo['DADOS-GERAIS']);
        $existingThing = Thing::where('id_lattes13', $curriculo['@attributes']['NUMERO-IDENTIFICADOR'])->first();
        if ($existingThing) {
            if (isset($request->universidade) && isset($request->ppg)) {
                if (isset($existingThing->affiliation)) {
                    $affiliation = json_decode($existingThing->affiliation, true);
                    foreach ($affiliation as $aff_check) {
                        if ($aff_check['universidade'] == $request->universidade && $aff_check['program'] == $request->ppg) {
                            return $existingThing;
                        } else {
                            $aff_count = count($affiliation) + 1;
                            $affiliation[$aff_count]['universidade'] = $request->universidade;
                            $affiliation[$aff_count]['program'] = $request->ppg;
                            $existingThing->affiliation = json_encode($affiliation);
                            $existingThing->save();
                            return $existingThing;
                        }
                    }
                } else {
                    $affiliation[0]['universidade'] = $request->universidade;
                    $affiliation[0]['program'] = $request->ppg;
                    $existingThing->affiliation = json_encode($affiliation);
                    $existingThing->save();
                    return $existingThing;
                }
            } else {
                return $existingThing;
            }
        } else {
            if (isset($request->universidade) && isset($request->ppg)) {
                $affiliation[0]['universidade'] = $request->universidade;
                $affiliation[0]['program'] = $request->ppg;
            } else {
                $affiliation[0]['universidade'] = '';
                $affiliation[0]['program'] = '';
            }
            $newThing = Thing::firstOrCreate([
                'type'=>'Person',
                'name' => $curriculo_dados_gerais['@attributes']['NOME-COMPLETO'],
                'id_lattes13' => $curriculo['@attributes']['NUMERO-IDENTIFICADOR'],
                'affiliation' => json_encode($affiliation)
            ]);
            return $newThing;
        }
    }
    public function searchForAuthor($author)
    {
        if (!empty((string)$author->attributes()->{'NRO-ID-CNPQ'})) {
            $existingThing = Thing::where('id_lattes13', (string)$author->attributes()->{'NRO-ID-CNPQ'})->first();
            if ($existingThing) {
                return $existingThing;
            } else {
                $newThing = Thing::firstOrCreate([
                    'type'=>'Person',
                    'name' => (string)$author->attributes()->{'NOME-COMPLETO-DO-AUTOR'},
                    'id_lattes13' => (string)$author->attributes()->{'NRO-ID-CNPQ'}
                ]);
                return $newThing;
            }
        } else {
            $existingThing = Thing::where('name', (string)$author->attributes()->{'NOME-COMPLETO-DO-AUTOR'})->first();
            if ($existingThing) {
                return $existingThing;
            } else {
                $newThing = Thing::firstOrCreate([
                    'type'=>'Person',
                    'name' => (string)$author->attributes()->{'NOME-COMPLETO-DO-AUTOR'}
                ]);
                return $newThing;
            }
        }

    }
    
    public function processaPalavrasChaveLattes($palavras_chave)
    {
        $palavras_chave = get_object_vars($palavras_chave);
        foreach (range(1, 6) as $number) {
            if (!empty($palavras_chave['@attributes']["PALAVRA-CHAVE-$number"])) {
                $array_result['about'][$number]['id'] = "";
                $array_result['about'][$number]['name'] = $palavras_chave['@attributes']["PALAVRA-CHAVE-$number"];
            }
        }
        if (isset($array_result)) {
            return $array_result;
        }
        unset($array_result);
    }

    public function validarISSN($issn)
    {
        // Remover possíveis traços e espaços em branco
        $issn = str_replace(['-', ' '], '', $issn);
        if (strlen($issn) !== 8) {
            return false;
        }
        // Formatar o ISSN no formato XXXX-XXXX
        $formattedISSN = substr($issn, 0, 4) . '-' . substr($issn, 4);
        return $formattedISSN;
    }
    
    public function processXML(Request $request)
    {
        if ($request->file) {
            $curriculo = simplexml_load_file($request->file);
            //dd($curriculo);
            // Create Thing
            $this->createThing($curriculo, $request);

            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'})) { 
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'}->{'TRABALHO-EM-EVENTOS'} as $trabalho) {
                    $record['name'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['TITULO-DO-TRABALHO'];
                    $record['doi'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['DOI'];
                    if (WorkController::checkIfRecordExists($record['name'], $record['doi'])) {
                        continue;
                    } else {
                        $record['datePublished'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['ANO-DO-TRABALHO'];
                        $record['type'] = "Trabalho em Evento";
                        $record['inLanguage'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['IDIOMA'];

                        $ISSN = $this->validarISSN((string)$trabalho->{'DETALHAMENTO-DO-ARTIGO'}['ISSN']);
                        if ($ISSN) {
                            if (!is_null(Qualis::where('issn', $ISSN)->first())) {
                                $ISSN_result = Qualis::where('issn', $ISSN)->first();
                                $record['releasedEvent'] = $ISSN_result['titulo'];
                            } else {
                                $record['releasedEvent'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['NOME-DO-EVENTO'];
                            }
                            $record['issn'] = $ISSN;
                        } else {
                            $record['issn'] = $ISSN;
                            $record['releasedEvent'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['NOME-DO-EVENTO'];
                        }
                        
                        $record['volumeNumber'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['VOLUME'];
                        $record['issueNumber'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['FASCICULO'];
                        $record['pageStart'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['PAGINA-INICIAL'];
                        $record['pageEnd'] = (string)$trabalho->{'DETALHAMENTO-DO-TRABALHO'}['PAGINA-FINAL'];
                        $i_autores = 0;
                        foreach ($trabalho->{'AUTORES'} as $author) {
                            $record_authors[$i_autores] = $this->searchForAuthor($author);
                            $record_authors[$i_autores]['function'] = 'Autor';
                            $i_autores++;
                        }
                        $record['author'] = array_unique($record_authors, SORT_REGULAR);
                        unset($record_authors);
                        if (isset($trabalho->{'PALAVRAS-CHAVE'})) {
                            $array_result_pc = $this->processaPalavrasChaveLattes($trabalho->{'PALAVRAS-CHAVE'});
                            if (isset($array_result_pc)) {
                                $record = array_merge_recursive($record, $array_result_pc);
                            }
                            unset($array_result_pc);
                        }
                        $work = new Work($record);
                        $work->save();
                        WorkController::indexRelations($work->id);
                    }
                    unset($record);
                    unset($trabalho);
                }
            }
            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'} as $artigo) {
                    $record['name'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['TITULO-DO-ARTIGO'];
                    $record['doi'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['DOI'];
                    if (WorkController::checkIfRecordExists($record['name'], $record['doi'])) {
                        continue;
                    } else {
                        $record['datePublished'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['ANO-DO-ARTIGO'];
                        $record['type'] = "Artigo";
                        $record['inLanguage'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['IDIOMA'];
                        
                        $ISSN = $this->validarISSN((string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['ISSN']);
                        if ($ISSN) {
                            if (!is_null(Qualis::where('issn', $ISSN)->first())) {
                                $ISSN_result = Qualis::where('issn', $ISSN)->first();
                                $record['isPartOf_name'] = $ISSN_result['titulo'];
                            } else {
                                $record['isPartOf_name'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['TITULO-DO-PERIODICO-OU-REVISTA'];
                            }
                            $record['issn'] = $ISSN;
                        } else {
                            $record['issn'] = $ISSN;
                            $record['isPartOf_name'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['TITULO-DO-PERIODICO-OU-REVISTA'];
                        }

                        $record['volumeNumber'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['VOLUME'];
                        $record['issueNumber'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['FASCICULO'];
                        $record['pageStart'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['PAGINA-INICIAL'];
                        $record['pageEnd'] = (string)$artigo->{'DETALHAMENTO-DO-ARTIGO'}['PAGINA-FINAL'];
                        $i_autores = 0;
                        foreach ($artigo->{'AUTORES'} as $author) {
                            $record['author'][$i_autores] = $this->searchForAuthor($author);
                            $record['author'][$i_autores]['function'] = 'Autor';
                            $i_autores++;
                        }
                        $i_about = 0;
                        foreach ($artigo->{'DETALHAMENTO-DO-ARTIGO'}->{'PALAVRAS-CHAVE'} as $palavra) {
                            $record['about'][$i_about]['id'] = "";
                            $record['about'][$i_about]['name'] = (string)$palavra;
                            $i_about++;
                        }
                        $work = new Work($record);
                        $work->save();
                        WorkController::indexRelations($work->id);
                    }
                    unset($record);
                    unset($artigo);
                }
            }
            return redirect('/works')->with('success', 'Trabalhos importados com sucesso!');
        }
    }
}
<?php

namespace App\Services;

use App\Http\Controllers\WorkController;
use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Support\Str;

class ThesisTSVService
{
    public function process($path)
    {
        $i = 0;
        if (($handle = fopen(storage_path('app/'.$path), "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 100000, "\t")) !== FALSE) {
                if ( $i==0 ) {
                    foreach ($data as $key => $value) {
                        $header[$key] = $value;
                    }
                    define('HEADER', $header);
                    $i++;
                    continue;
                } else {
                    $work = new Work();
                    foreach ($data as $key => $value) {
                        $label = data_get(HEADER, $key);
                        if ($label == 'NM_SUBTIPO_PRODUCAO') {
                            $work->type = $value;
                        }
                        if ($label == 'NM_PRODUCAO') {
                            $work->name = $value;
                        }
                        if ($label == 'TituloTese') {
                            $work->name = $value;
                        }
                        if ($label == 'AN_BASE') {
                            $work->datePublished = $value;
                        }
                        if ($label == 'AnoBase') {
                            $work->datePublished = $value;
                        }
                        if ($label == 'NM_IDIOMA') {
                            $work->inLanguage = $value;
                        }
                        if ($label == 'Idioma') {
                            $work->inLanguage = $value;
                        }
                        if ($label == 'DS_URL_TEXTO_COMPLETO') {
                            $work->url = $value;
                        }
                        if ($label == 'URLTextoCompleto') {
                            $work->url = $value;
                        }
                        if ($label == 'DS_RESUMO') {
                            $work->abstract = $value;
                        }
                        if ($label == 'ResumoTese') {
                            $work->abstract = $value;
                        }
                        if ($label == 'NM_PROGRAMA') {
                            $work->inSupportOf = $value;
                        }
                        if ($label == 'NomePrograma') {
                            $work->inSupportOf = $value;
                        }
                        if ($label == 'NM_DISCENTE') {
                            $author_array = [];
                            $author_array[0]['id'] = '';
                            $author_array[0]['type'] = 'Person';
                            $author_array[0]['name'] = $value;
                            $author_array[0]['function'] = 'Autor';
                        }
                        if ($label == 'Autor') {
                            $author_array = [];
                            $author_array[0]['id'] = '';
                            $author_array[0]['type'] = 'Person';
                            $author_array[0]['name'] = $value;
                            $author_array[0]['function'] = 'Autor';
                        }
                        if ($label == 'NM_ORIENTADOR') {
                            $author_array[1]['id'] = '';
                            $author_array[1]['type'] = 'Person';
                            $author_array[1]['name'] = $value;
                            $author_array[1]['function'] = 'Orientador';
                            $work->author = $author_array;
                            unset($author_array);
                        }
                        if ($label == 'Orientador_1') {
                            $author_array[1]['id'] = '';
                            $author_array[1]['type'] = 'Person';
                            $author_array[1]['name'] = $value;
                            $author_array[1]['function'] = 'Orientador';
                            $work->author = $author_array;
                            unset($author_array);
                        }
                        if ($label == 'NM_ENTIDADE_ENSINO') {
                            $work->sourceOrganization = $value;
                        }
                        if ($label == 'NomeIes') {
                            $work->sourceOrganization = $value;
                        }
                        if ($label == 'NR_PAGINAS') {
                            $work->numberOfPages = $value;
                        }
                        if ($label == 'NumeroPaginas') {
                            $work->numberOfPages = $value;
                        }
                        if ($label == 'DS_PALAVRA_CHAVE') {
                            $keywords = explode(';', $value);
                            foreach ($keywords as $key => $value) {
                                $keywords_array[$key]['id'] = "";
                                $keywords_array[$key]['name'] = $value;
                            }
                            $work->about = $keywords_array;
                            unset($keywords_array);
                        }
                        if ($label == 'PalavrasChave') {
                            $keywords = explode(';', $value);
                            foreach ($keywords as $key => $value) {
                                $keywords_array[$key]['id'] = "";
                                $keywords_array[$key]['name'] = $value;
                            }
                            $work->about = $keywords_array;
                            unset($keywords_array);
                        }
                    }
                    $saved_id = $work->save();
                    if (!$saved_id) {
                        Log::error('Erro ao salvar o trabalho: ' . $data['name']);
                    } else {
                        WorkController::indexRelations($work->id);
                    }
                    continue;
                }
            }
        }
        fclose($handle);
        return redirect('/works')->with('success', 'Trabalhos importados com sucesso!');
    }
}
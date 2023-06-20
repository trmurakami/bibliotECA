<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;

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
                    $record['type'] = (string)$trabalho->{'DADOS-BASICOS-DO-TRABALHO'}['NATUREZA'];
                    $i_autores = 0;
                    foreach ($trabalho->{'AUTORES'} as $autor) {
                        $record['author'][$i_autores]['name'] = (string)$autor->attributes()->{'NOME-COMPLETO-DO-AUTOR'};
                        $i_autores++;
                    }
                    $work = new Work($record);
                    $work->save();
                    unset($record);
                }
            }
            if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'})) {
                foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'}->{'ARTIGO-PUBLICADO'} as $artigo) {
                    $record['name'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['TITULO-DO-ARTIGO'];
                    $record['datePublished'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['ANO-DO-ARTIGO'];
                    $record['type'] = (string)$artigo->{'DADOS-BASICOS-DO-ARTIGO'}['NATUREZA'];
                    $i_autores = 0;
                    foreach ($artigo->{'AUTORES'} as $autor) {
                        $record['author'][$i_autores]['name'] = (string)$autor->attributes()->{'NOME-COMPLETO-DO-AUTOR'};
                        $i_autores++;
                    }
                    $work = new Work($record);
                    $work->save();
                    unset($record);
                }
            }
            // if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'})) {
            //     foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'LIVROS-PUBLICADOS-OU-ORGANIZADOS'}->{'LIVRO-PUBLICADO-OU-ORGANIZADO'} as $livro) {
            //         $work = new Work();
            //         $work['name'] = (string)$livro->{'DADOS-BASICOS-DO-LIVRO'}['TITULO-DO-LIVRO'];
            //         $work['datePublished'] = (string)$livro->{'DADOS-BASICOS-DO-LIVRO'}['ANO'];
            //         $work['type'] = (string)$livro->{'DADOS-BASICOS-DO-LIVRO'}['NATUREZA'];
            //         $i_autores = 0;
            //         foreach ($trabalho->{'AUTORES'} as $autor) {
            //             $autor_array = [];
            //             $autor_array['name'] = $autor->{'NOME-COMPLETO-DO-AUTOR'};
            //             $work['authors'][$i_autores] = (string)$autor_array;
            //             $i_autores++;
            //         }
            //         $work->save();
            //     }
            // }
            // if (isset($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'})) {
            //     foreach ($curriculo->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'}->{'CAPITULO-DE-LIVRO-PUBLICADO'} as $capitulo) {
            //         $work = new Work();
            //         $work['name'] = (string)$capitulo->{'DADOS-BASICOS-DO-CAPITULO'}['TITULO-DO-CAPITULO-DO-LIVRO'];
            //         $work['datePublished'] = (string)$capitulo->{'DADOS-BASICOS-DO-CAPITULO'}['ANO'];
            //         $work['type'] = (string)$capitulo->{'DADOS-BASICOS-DO-CAPITULO'}['NATUREZA'];
            //         $i_autores = 0;
            //         foreach ($trabalho->{'AUTORES'} as $autor) {
            //             $autor_array = [];
            //             $autor_array['name'] = $autor->{'NOME-COMPLETO-DO-AUTOR'};
            //             $work['authors'][$i_autores] = (string)$autor_array;
            //             $i_autores++;
            //         }
            //         $work->save();
            //     }
            // }
            
        }
    }
}
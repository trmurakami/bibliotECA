<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TeamTNT\TNTSearch\TNTSearch;
use App\Http\Controllers\Controller;
use App\Models\StringClassifier;

class ClassificadorController extends Controller
{
    public function consulta()
    {
        return view('classificador.consulta');
    }

    public function processarConsulta(Request $request)
    {
        $string = $request->input('string');

        $classifier = new StringClassifier;
        $results = $classifier->predict($string);

        return view('classificador.resultados', compact('results', 'request'));
    }

    public function treinamento()
    {
        return view('classificador.treinamento');
    }

    public function processarTreinamento(Request $request)
    {
        $strings = explode("\n", $request->input('strings'));
        $labels = explode("\n", $request->input('labels'));

        $classifier = new StringClassifier;
        $classifier->train($strings, $labels);

        return redirect()->route('classificador.consulta')->with('success', 'O modelo foi treinado com sucesso!');
    }

    public function uploadTSVTreinamento(Request $request) {
        $file = $request->file('file');
        if (($handle = fopen($file, "r")) !== FALSE) {
            $classifier = new StringClassifier;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $classifier->train([$data[0]], [$data[1]]);
            }
            fclose($handle);
        }
    }
}
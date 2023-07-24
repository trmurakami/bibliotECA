<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class GexfController extends Controller
{
    public function exportGexf()
    {
        $works = Work::with('abouts')->get();

        $gexf = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><gexf xmlns="http://www.gexf.net/1.3"
    version="1.3">
    <graph mode="static"></graph>
</gexf>');
$graph = $gexf->graph;

// Adicionar nós (assuntos)
foreach ($works as $work) {
foreach ($work->abouts as $about) {
$node = $graph->addChild('node');
$node->addAttribute('id', $about->id);
$node->addAttribute('label', $about->name);
}
}

// Adicionar arestas (relacionamentos entre os assuntos)
foreach ($works as $work) {
$aboutIds = $work->abouts->pluck('id')->toArray();

for ($i = 0; $i < count($aboutIds) - 1; $i++) { for ($j=$i + 1; $j < count($aboutIds); $j++) { $edge=$graph->
    addChild('edge');
    $edge->addAttribute('source', $aboutIds[$i]);
    $edge->addAttribute('target', $aboutIds[$j]);
    }
    }
    }

    $filename = 'graph.gexf';
    $filepath = public_path($filename);
    $gexf->asXML($filepath);

    return response()->download($filepath)->deleteFileAfterSend();
    }
    }
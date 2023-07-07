<?php

namespace App\Services;

use App\Http\Controllers\WorkController;
use Illuminate\Http\Request;
use App\Models\Work;

class ThesisTSVService
{
    public function process($path)
    {
        $i = 0;
        if (($handle = fopen(storage_path('app/'.$path), "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 10000, "\t")) !== FALSE) {
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
                        if ($label == 'AN_BASE') {
                            $work->datePublished = $value;
                        }
                    }
                    $saved = $work->save();
                    if (!$saved) {
                        Log::error('Erro ao salvar o trabalho: ' . $data['name']);
                    }
                    continue;
                }
            }
        

            // while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
            //     if ($i == 0) {
            //         foreach ($data as $key => $value) {
            //             $header[$key] = $value;
            //         }
            //         define('HEADER', $header);
            //         $i++;
            //         continue;
            //     } else {
            //         $rowData = [];
            //         $rowData['type'] = 'Tese';
            //         foreach ($data as $key => $value) {
            //             $label = data_get(HEADER, $key);
            //             $rowData[$label] = $value;
            //         }
            //         $resultUpload = WorkController::storeFromCSV($rowData);
            //         unset($rowData);
            //         continue;
            //     }
            // }
        }
        if ($resultUpload == true) {
            echo "Arquivo processado com sucesso!";
        } else {
            echo "Erro ao processar o arquivo!";
        }
        fclose($handle);
    }
}
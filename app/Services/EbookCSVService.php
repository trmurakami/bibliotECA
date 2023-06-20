<?php

namespace App\Services;

use App\Http\Controllers\WorkController;
use Illuminate\Http\Request;

class EbookCSVService
{
    public function process($path)
    {

        if (($handle = fopen(storage_path('app/'.$path), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                if ($i == 0) {
                    foreach ($data as $key => $value) {
                        $header[$key] = $value;
                    }
                    define('HEADER', $header);
                    $i++;
                    continue;
                }
                $row = $data;
                $rowData = [];
                foreach ($row as $key => $value) {
                    if (array_key_exists($key, HEADER)) {
                        $key = HEADER[$key];
                    }
                    $rowData[$key] = $value;
                }
                $i++;
                WorkController::storeFromCSV($rowData);
            }
            fclose($handle);
        }
    }
}
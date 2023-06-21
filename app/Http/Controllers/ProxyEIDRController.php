<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyEIDRController extends Controller

{
    // function to store file in 'upload' folder
    public function getEIDR(Request $request)
    {
        $xml = simplexml_load_file('https://resolve.eidr.org/EIDR/object/10.5240/'.$request->eidr.'/?type=Full&followAlias=true');
        return response()
            ->json(
                $xml->BaseObjectData,
                200
            );
    }
}
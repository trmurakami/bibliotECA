<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CreativeWork;

class ProxyOAIPMHController extends Controller

{
    // function to store file in 'upload' folder
    public function getOAIPMHIdentify(Request $request)
    {
        $xml = simplexml_load_file($request->url . '?verb=Identify');
        return response()
            ->json(
                $xml,
                200
            );
    }

    public function listMetadataFormats(Request $request)
    {
        $xml = simplexml_load_file($request->url . '?verb=ListMetadataFormats');
        return response()
        ->json(
            $xml,
            200
        );
    }

    public function listSets(Request $request)
    {
        $xml = simplexml_load_file($request->url . '?verb=ListSets');
        return response()
        ->json(
            $xml,
            200
        );
    }

    public function listOAIPMH(Request $request)
    {

        if (!$request->per_page) {
            $request->per_page = 10;
        }

        $query = CreativeWork::select(['id', 'name', 'type', 'url', 'oaipmh', 'oaimetadataformat', 'oaiset'])
        ->withCount('hasPart');

        $query->where('type', 'ilike', "%periodical%");

        if ($request->search) {
            $query->where('name', 'ilike', "%$request->search%");
        }

        $queryResult = $query->orderByDesc('id')->paginate($request->per_page);

        //return $queryResult;

        return response()
        ->json(
            $queryResult,
            200
        );
    }
}
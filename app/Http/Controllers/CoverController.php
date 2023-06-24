<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Medlib\BookCover\BookCover;

class CoverController extends Controller
{
    public function show(Request $request)
    {
        if (!file_exists('files/covers/' . $request->id . '.png')) {

            $cover = new BookCover();
            $cover->setTitle($request->title)
                ->setSubtitle($request->subtitle)
                ->setCreators($request->creators)
                ->setEdition($request->edition)
                ->setPublisher($request->publisher)
                ->setDatePublished($request->datePublished)
                ->randomizeBackgroundColor();
            $headers[] = header('Content-Type: image/png');
            return response($cover->getImageBase64(), 200)->header('Content-type', 'image/png');

        } else {
            $path = 'files/covers/' . $request->id . '.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $headers[] = header('Content-Type: image/png');
            return response(
                $base64,
                200,
                ['Content-Type' => 'image/png']
            );
        }
    }

    public static function internalCover(CreativeWork $request)
    {
        if (!file_exists('files/covers/' . $request->id . '.png')) {

            //dd($request);
            if ($request->author == null) {
                $author = "";
            } else {
                $author = $request->author[0]['name'];
            }

            if ($request->publisher == null) {
                $publisher = "";
            } else {
                $publisher = $request->publisher;
            }

            $cover = new BookCover();
            $cover->setTitle($request->name)
                ->setSubtitle($request->subtitle)
                ->setCreators($author)
                ->setEdition($request->edition)
                ->setPublisher($publisher)
                ->setDatePublished($request->datePublished)
                ->randomizeBackgroundColor();
            return $cover->getImageBase64();
        } else {
            $path = 'files/covers/' . $request->id . '.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $headers[] = header('Content-Type: image/png');
            return $base64;
        }
    }

    public function destroy(Request $request)
    {
        if (!file_exists('files/covers/' . $request->id . '.jpg')) {

            if (file_exists('files/covers/' . $request->id . '.png')) {
                unlink('files/covers/' . $request->id . '.png');
                return response()->json('Arquivo excluído', 204);
            }
            return response()->json(
                ['erro' => 'Recurso não encontrado'],
                404
            );
        } else {
            unlink('files/covers/' . $request->id . '.jpg');
            return response()->json('Arquivo excluído', 204);
        }

    }

    public function generateCover(Request $request) {
        $cover = new BookCover();
        $cover->setTitle($request->title)
        ->setSubtitle($request->subtitle)
        ->setCreators($request->creators)
        ->setEdition($request->edition)
        ->setPublisher($request->publisher)
        ->setDatePublished($request->datePublished)
        ->randomizeBackgroundColor();

        $headers[] = header('Content-Type: image/png');

        return response($cover->getImageBlob())->header('Content-type', 'image/png');
        //return $cover->getImageBlob();
    }

}
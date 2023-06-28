<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EbookCSVService;
use App\Services\ScopusCSVService;
use App\Services\BookCoverService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UploadController extends Controller
{
    public function index()
    {
        return view('upload.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xml,txt,png,jpg,jpeg|max:102400',
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $newFilename = $request->input('new_filename');
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $newFilename);

            // Verificar o tipo do arquivo e chamar o serviço correspondente
            if ($file->getClientOriginalExtension() === 'csv') {
                $filename = $path;

                if (strpos($filename, 'ebooks.csv') !== false) {
                    $ebookCSVService = new EbookCSVService();
                    $ebookCSVService->process($path);
                } elseif (strpos($filename, 'scopus.csv') !== false) {
                    $scopusCSVService = new ScopusCSVService();
                    $scopusCSVService->process($path);
                }
            } elseif ($file->getClientOriginalExtension() === 'xml') {
                // Lógica para processar o arquivo XML
            } elseif ($file->getClientOriginalExtension() === 'txt') {
                // Lógica para processar o arquivo TXT
            } elseif ($file->getClientOriginalExtension() === 'png' || $file->getClientOriginalExtension() === 'jpg' || $file->getClientOriginalExtension() === 'jpeg') {
                $filename = $file->getClientOriginalName();
                if (strpos($filename, 'covers') !== false) {
                    $coverService = new BookCoverService();
                    $number = $request->input('number');
                    $coverService->upload($path, $number);
                }
            }

            //return redirect()->back()->with('success', 'Arquivo enviado e processado com sucesso.');
        }

        //return redirect()->back()->with('error', 'Erro ao enviar o arquivo.');
    }
}
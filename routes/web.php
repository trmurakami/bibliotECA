<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorksAPIController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ClassificadorController;
use App\Http\Controllers\LattesController;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\WorkThingController;
use App\Http\Controllers\MARCController;
use App\Http\Controllers\MARCQAController;
use App\Http\Controllers\AboutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('works', WorkController::class);
Route::resource('works', WorkController::class)->only(['create'])->middleware('auth');
Route::resource('works', WorkController::class)->only(['edit']);

Route::get('/editor', function () {
    return view('works.createnew');
});

Route::get('upload', [UploadController::class, 'index']);
Route::post('upload', [UploadController::class, 'upload'])->name('upload.upload');


Route::get('/classificador/consulta', [ClassificadorController::class, 'consulta'])->name('classificador.consulta');
Route::post('/classificador/consulta', [ClassificadorController::class, 'processarConsulta'])->name('classificador.processarConsulta');
Route::get('/classificador/treinamento', [ClassificadorController::class, 'treinamento'])->name('classificador.treinamento');
Route::post('/classificador/treinamento', [ClassificadorController::class, 'processarTreinamento'])->name('classificador.processarTreinamento');

Route::post('/lattes', [LattesController::class, 'processXML'])->name('lattes.processXML');


Route::post('/works/{work}/attach', [WorkThingController::class, 'attachThing'])->name('works.attachThing');
Route::delete('/works/{work}/detach/{thing}', [WorkThingController::class, 'detachThing'])->name('works.detachThing');

Route::resource('things', ThingController::class);
Route::resource('abouts', AboutController::class);

Route::get('/cutter', function () {
    return view('cutter.cutter');
});

Route::post('marc', [MARCController::class, 'processMARC'])->name('marc.processMARC');

Route::get('graficos', [WorkController::class, 'graficos'])->name('works.graficos');

Route::get('/marc', function () {
    return view('marc.upload');
});

Route::post('marcqareport', [MARCQAController::class, 'marcQAReport'])->name('marc.report');

Route::get('/phpinfo', function () {
    return view('phpinfo');
});
@extends('layouts.layout')

@section('title', 'BibliotECA - Upload')

@section('content')

<h4 class="mt-3">MARC Quality Analysis - Relatório</h4>
<form method="POST" action="/marcqareport" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo MARC</label>
        <p>Tamanho máximo do arquivo {{ (int)(ini_get('post_max_size')) }} MB</p>
        <input class="form-control" type="file" for="file" accept=".mrc" name="file">
        <input type="text" name="new_filename" value="marc.mrc" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
<hr>
<h4 class="mt-3">MARC Quality Analysis - JSON</h4>
<form method="POST" action="/api/marcqa" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo MARC</label>
        <input class="form-control" type="file" for="file" accept=".mrc" name="file">
        <input type="text" name="new_filename" value="marc.mrc" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
<hr>
<h4 class="mt-3">MARC Quality Analysis - Exportar campo</h4>
<form method="POST" action="/api/marcqaexportfield" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <div class="input-group mb-3">
            <span class="input-group-text" id="file">Enviar arquivo .mrc - Tamanho máximo do arquivo
                {{ (int)(ini_get('post_max_size')) }} MB</span>
            <input class="form-control" type="file" for="file" accept=".mrc" name="file">
            <input type="text" name="new_filename" value="marc.mrc" hidden>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="marcfield" name="marcfield">
            <label for="marcfield">Campo MARC (Ex. 245$a ou 245)</label>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
</form>
<hr>
<h4 class="mt-3">MARC Quality Analysis - Relatório indicador2 no campo título (245)</h4>
<form method="POST" action="/api/marcqareportTitleInd2" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo MARC</label>
        <input class="form-control" type="file" for="file" accept=".mrc" name="file">
        <input type="text" name="new_filename" value="marc.mrc" hidden>
    </div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="onlyErrors" name="onlyErrors">
        <label class="form-check-label" for="onlyErrors">Retornar somente os registros com erros</label>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Enviar</button>
</form>
<hr>
<h4 class="mt-3">MARC Quality Analysis - Corrigir indicador2 no campo título (245) automaticamente</h4>
<form method="POST" action="/api/marcqacorrectTitleInd2" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo MARC</label>
        <input class="form-control" type="file" for="file" accept=".mrc" name="file">
        <input type="text" name="new_filename" value="marc.mrc" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

@endsection
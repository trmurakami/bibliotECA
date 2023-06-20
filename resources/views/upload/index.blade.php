@extends('layouts.layout')

@section('title', 'BibliotECA - Upload')

@section('content')

<h2>Upload de Arquivo CSV</h2>

@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
<div style="color: red;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('upload.upload') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo</label>
        <input class="form-control" type="file" for="file" accept=".csv, .txt" name="file">
        <input type="text" name="new_filename" value="ebooks.csv" hidden>
    </div>
    <button type="submit">Enviar</button>
</form>


<h2>Upload de Arquivo XML do Lattes</h2>

<form method="POST" action="{{ route('lattes.processXML') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo</label>
        <input class="form-control" type="file" for="file" accept=".xml" name="file">
        <input type="text" name="new_filename" value="curriculo.xml" hidden>
    </div>
    <button type="submit">Enviar</button>
</form>

@endsection
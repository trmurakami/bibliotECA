@extends('layouts.layout')

@section('title', 'BibliotECA - Upload')

@section('content')



<h1>Upload de Arquivo CSV</h1>

@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
<div style="color: red;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('upload.upload') }}" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="file">Arquivo</label>
        @csrf
        <input type="file" name="file" accept=".csv, .txt">
        <input type="text" name="new_filename" value="ebooks.csv" hidden>
    </div>
    <button type="submit">Enviar</button>
</form>
</body>

</html>

@endsection
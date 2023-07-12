@extends('layouts.layout')

@section('title', 'BibliotECA - Consulta')

@section('content')

<h2 class="mt-3">Consulta no classificador</h2>

<form method="POST" action="{{ route('classificador.consulta') }}">
    @csrf
    <div class="mb-3">
        <label for="string" class="form-label">Digite a string para consulta:</label>
        <input type="text" name="string" class="form-control" id="string">
    </div>
    <button type="submit" class="btn btn-primary">Classificar</button>
</form>

@endsection
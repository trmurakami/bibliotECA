@extends('layouts.layout')

@section('title', 'BibliotECA - Treinamento do classificador')

@section('content')

<h2>Treinamento do classificador</h2>

<form method="POST" action="{{ route('classificador.treinamento') }}">
    @csrf

    <div class="mb-3">
        <label for="strings" class="form-label">Digite as strings para treinamento (uma por linha):</label>
        <textarea class="form-control" id="strings" rows="3" name="strings" required></textarea>
    </div>
    <div class="mb-3">
        <label for="labels">Digite os rótulos correspondentes (uma por linha):</label>
        <textarea class="form-control" name="labels" id="labels" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection
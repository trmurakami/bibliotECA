@extends('layouts.layout')

@section('title', 'BibliotECA - Resultado da consulta no classificador')

@section('content')

<h3 class="mt-3 mb-3">Resultados da consulta no classificador</h3>

<div class="alert alert-primary" role="alert">
    <p>Você pesquisou por: <strong>{{ $request->string }}</strong></p>
    <p>A nota obtida foi: <strong>{{ $results['likelihood'] }}</strong></p>
    <p>A classificação obtida foi: <strong>{{ $results['label'] }}</strong></p>
</div>

@endsection
@extends('layouts.layout')

@section('title', 'BibliotECA - Resultado da consulta')

@section('content')

<h3 class="mt-3 mb-3">Resultados da consulta</h3>

{{ print_r($results, true) }}

@endsection
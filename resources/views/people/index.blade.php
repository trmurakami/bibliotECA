@extends('layouts.layout')

@section('title', 'BibliotECA - Pessoas')

@section('content')

@foreach ($people as $person)

{{ print_r($person->name, true)}} - {{ print_r($person->id_lattes13, true)}}

<br />

@endforeach

@endsection
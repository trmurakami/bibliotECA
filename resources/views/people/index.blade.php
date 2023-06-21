@extends('layouts.layout')

@section('title', 'BibliotECA - Pessoas')

@section('content')

@foreach ($people as $person)

<a href="/works?author={{ $person->name }}">{{ print_r($person->name, true)}}</a> -
{{ print_r($person->id_lattes13, true)}} - {{ $person->works_count }}



<br />

@endforeach

@endsection
@extends('layouts.layout')

@section('title', 'BibliotECA - MARC Quality Analysis - Relatório')

@section('content')

<h4 class="mt-3">MARC Quality Analysis - Relatório</h4>

<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Campos preenchidos ({{$tags['recordCount']}} registros)</h4>
    <p>Foram encontrados os seguintes campos preenchidos:</p>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Quantidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags['tagCount'] as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
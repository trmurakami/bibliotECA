@extends('layouts.layout')

@section('title', 'BibliotECA - Pessoas')

@section('content')

<div class="row">
    <div class="col col-lg-12">
        <h2 class="mt-2 mb-2">Pessoas</h2>
        <form action="/people" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Pesquisar por nome" name="name"
                    value="{{ request()->name }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
                </div>
            </div>
            <x-paginationPeople :people="$people" />
            <ul class="list-group">
                @foreach ($people as $person)

                <li class="list-group-item">
                    <div class="row">
                        <div class="col-8">
                            <a href="/works?author={{ $person->name }}">{{ print_r($person->name, true)}}</a>
                            @if ($person->id_lattes13)
                            <a href="https://lattes.cnpq.br/{{ $person->id_lattes13 }}" target="_blank"><span
                                    class="badge text-bg-info">Lattes</span></a>
                            @endif
                        </div>
                        <div class="col-4"><span class="badge text-bg-primary">{{ $person->works_count }}</span></div>
                    </div>
                </li>

                @endforeach
            </ul>
    </div>
</div>

@endsection
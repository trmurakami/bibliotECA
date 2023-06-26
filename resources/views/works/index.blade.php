@extends('layouts.layout')

@section('title', 'BibliotECA - Resultado da busca')

@section('content')

<div class="row">

    @if ($message = Session::get('success'))
    <div class="alert alert-success mt-3">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="col col-lg-12">
        <h3 class="mt-2">Resultado da busca</h3>
        <form action="/works" method="get">
            <div class="input-group">
                <input type="text" class="form-control"
                    placeholder="Pesquisar no título (Deixe em branco e clique em buscar para exibir todo o acervo)"
                    name="name">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>
        <div class="d-flex mt-3 mb-3">
            <div class="mx-auto">
                {!! $works->links() !!}
            </div>
        </div>
    </div>

    <div class="col col-lg-8">
        @foreach ($works as $work)
        <x-record :work="$work" />
        @endforeach
    </div>
    <div class="col col-lg-4">
        <h3>Refinar resultados <a href="/works" class="btn btn-warning">Limpar busca</a> </h3>

        <div class="accordion" id="facets">


            <x-facet field="type" fieldName="Tipo" :request="$request" />
            <x-facet field="name" fieldName="Título" :request="$request" />
            <x-facet field="datePublished" fieldName="Ano de publicação" :request="$request" />
            <x-facetAuthors :request="$request" />
            <x-facet field="isPartOf_name" fieldName="Publicação" :request="$request" />
            <x-facet field="releasedEvent" fieldName="Nome do evento" :request="$request" />
            <x-facet field="inLanguage" fieldName="Idioma" :request="$request" />
            <x-facet field="issn" fieldName="ISSN" :request="$request" />
            <x-facet field="publisher" fieldName="Editora" :request="$request" />
        </div>

    </div>
    <div class="d-flex mt-3 mb-3">
        <div class="mx-auto">
            {!! $works->links() !!}
        </div>
    </div>
</div>



@endsection
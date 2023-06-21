@extends('layouts.layout')

@section('title', 'BibliotECA - Resultado da busca')

@section('content')

<div class="row">

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="col col-lg-12">
        <h3 class="mt-2">Resultado da busca por "{{ $request->name }}" no campo título</h3>
        <form action="/works" method="get">
            <div class="input-group">
                <input type="text" class="form-control"
                    placeholder="Pesquisar no título (Deixe em branco e clique em buscar para exibir todo o acervo)"
                    name="name">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        <p>Foram encontrados {{ $works->total() }} resultados para a busca "{{ $request->name }}". Exibindo página
            {{ $works->currentPage() }} de {{ $works->lastPage() }} ({{ $works->perPage() }} resultados por página)</p>


        <x-pagination :works="$works" />
    </div>

    <div class="col col-lg-8">
        @foreach ($works as $work)
        <x-record :work="$work" />
        @endforeach
    </div>
    <div class="col col-lg-4">
        <h3>Refinar resultados</h3>

        <div class="accordion" id="facets">


            <x-facet field="type" fieldName="Tipo" :request="$request" />
            <x-facet field="name" fieldName="Título" :request="$request" />
            <x-facet field="datePublished" fieldName="Ano de publicação" :request="$request" />
            <x-facetAuthors :request="$request" />
            <x-facet field="isPartOf_name" fieldName="Publicação" :request="$request" />
            <x-facet field="releasedEvent" fieldName="Nome do evento" :request="$request" />
            <x-facet field="inLanguage" fieldName="Idioma" :request="$request" />
            <x-facet field="issn" fieldName="ISSN" :request="$request" />
        </div>

    </div>
    <x-pagination :works="$works" />
</div>



@endsection
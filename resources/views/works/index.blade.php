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
        <h3 class="mt-2">
            Resultado da busca <span class="badge text-bg-light fw-lighter">Exibindo
                {{ $works->firstItem() }}
                a {{ $works->lastItem() }} de {{ $works->total() }} registros</span>
        </h3>
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
        @if ($works->count() == 0)
        <div class="alert alert-warning" role="alert">
            Nenhum registro encontrado
        </div>
        @endif

        @foreach ($works as $work)
        <x-record :work="$work" />
        @endforeach
    </div>
    <div class="col col-lg-4">
        <h3>Refinar resultados <a href="/works" class="btn btn-warning">Limpar busca</a> </h3>

        @if (
        $request->has('name')||
        $request->has('type')||
        $request->has('datePublished')||
        $request->has('author')||
        $request->has('isPartOf_name')||
        $request->has('releasedEvent')||
        $request->has('inLanguage')||
        $request->has('issn')||
        $request->has('publisher')
        )
        <div class="alert alert-light" role="alert">
            Filtros ativos: <br>
            @foreach ($request->all() as $key => $value)
            @if ($key != 'page')
            <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                @php
                if ($key == 'author') {
                $key_name = 'Autor';
                }
                if ($key == 'name') {
                $key_name = 'Título';
                }
                if ($key == 'type') {
                $key_name = 'Tipo';
                }
                if ($key == 'datePublished') {
                $key_name = 'Ano de publicação';
                }
                if ($key == 'isPartOf_name') {
                $key_name = 'Publicação';
                }
                if ($key == 'releasedEvent') {
                $key_name = 'Nome do evento';
                }
                if ($key == 'inLanguage') {
                $key_name = 'Idioma';
                }
                if ($key == 'issn') {
                $key_name = 'ISSN';
                }
                if ($key == 'publisher') {
                $key_name = 'Editora';
                }
                @endphp


                <a type="button" class="btn btn-outline-warning mb-1"
                    href="/works?{{ http_build_query(array_diff_key($request->all(), [$key => $value])) }}">
                    {{ $key_name }}: {{ $value }} (X)
                </a>
            </div>
            @endif
            @endforeach
        </div>
        @endif


        <div class="accordion" id="facets">


            <x-facet field="type" fieldName="Tipo" :request="$request" />
            <x-facet field="datePublished" fieldName="Ano de publicação" :request="$request" />
            <x-facet-relations field="authors" fieldName="Autores" :request="$request" />
            <x-facet-relations field="abouts" fieldName="Assuntos" :request="$request" />
            <x-facet field="isPartOf_name" fieldName="Publicação" :request="$request" />
            <x-facet field="releasedEvent" fieldName="Nome do evento" :request="$request" />
            <x-facet field="inLanguage" fieldName="Idioma" :request="$request" />
            <x-facet field="issn" fieldName="ISSN" :request="$request" />
            <x-facet field="publisher" fieldName="Editora" :request="$request" />
            <x-facet field="sourceOrganization" fieldName="Instituição" :request="$request" />
            <x-facet field="inSupportOf" fieldName="Nome do PPG" :request="$request" />
        </div>

    </div>
    <div class="d-flex mt-3 mb-3">
        <div class="mx-auto">
            {!! $works->links() !!}
        </div>
    </div>
</div>



@endsection
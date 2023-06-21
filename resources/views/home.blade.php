@extends('layouts.layout')

@section('title', 'BibliotECA')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <form action="/works" method="get">
            <div class="input-group">
                <input type="text" class="form-control"
                    placeholder="Pesquisar no título (Deixe em branco e clique em buscar para exibir todo o acervo)"
                    name="name">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar no título</button>
                </div>
            </div>
        </form>
        <form action="/works" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquisar por nome do autor" name="author">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar autor</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
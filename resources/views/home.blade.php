@extends('layouts.layout')

@section('title', 'BibliotECA')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <form action="/works" method="get" class="mt-5 mb-5">
            <div class="input-group">
                <input type="text" class="form-control"
                    placeholder="Pesquisar no título (Deixe em branco e clique em buscar para exibir todo o acervo)"
                    name="name">
                <button class="btn btn-primary" type="submit">Buscar no título</button>
            </div>
        </form>
        <form action="/works" method="get" class="mt-5 mb-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquisar por nome do autor" name="author">
                <button class="btn btn-primary" type="submit">Buscar autor</button>
            </div>
        </form>
        <form method="POST" action="{{ route('classificador.consulta') }}" class="mt-5 mb-5">
            @csrf
            <div class="mb-3 input-group">
                <input type="text" name="string" class="form-control" id="string"
                    placeholder="Faça uma pergunta sobre a BibliotECA">
                <button type="submit" class="btn btn-primary">Perguntar</button>
            </div>
        </form>
    </div>
</div>

@endsection
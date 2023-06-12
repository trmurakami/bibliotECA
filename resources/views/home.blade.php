@extends('layout.layout')

@section('title', 'BibliotECA')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <form action="#" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquisar" name="q">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
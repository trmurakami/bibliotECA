@extends('layouts.layout')

@section('title', 'BibliotECA - Assuntos')

@section('content')

<div class="row">
    <div class="col col-lg-12">
        <h2 class="mt-2 mb-2">Assuntos</h2>
        <form action="/abouts" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Pesquisar por nome" name="name"
                    value="{{ request()->name }}">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
            <div class="d-flex mt-3 mb-3">
                <div class="mx-auto">
                    {!! $abouts->links() !!}
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($abouts as $about)

                <li class="list-group-item">
                    <div class="d-flex bd-highlight">
                        <div class="w-100 bd-highlight">
                            <a href="/works?author={{ $about->name }}">{{ print_r($about->name, true)}}</a>
                        </div>
                        <div class="flex-shrink-1 bd-highlight"><span
                                class="badge text-bg-primary">{{ $about->works_count }}</span>
                        </div>
                    </div>
                </li>

                @endforeach
            </ul>
            <div class="d-flex mt-3 mb-3">
                <div class="mx-auto">
                    {!! $abouts->links() !!}
                </div>
            </div>
    </div>
</div>

@endsection
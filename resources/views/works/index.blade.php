@extends('layout.layout')

@section('title', 'BibliotECA - Resultado da busca')

@section('content')

<div class="row">

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="col col-lg-12">
        <h3 class="mt-2">Resultado da busca por "{{ $request->search }}" no campo título</h3>
        <form action="/works" method="get">
            <div class="input-group">
                <input type="text" class="form-control"
                    placeholder="Pesquisar no título (Deixe em branco e clique em buscar para exibir todo o acervo)"
                    name="search">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        <p>Foram encontrados {{ $works->total() }} resultados para a busca "{{ $request->search }}". Exibindo página
            {{ $works->currentPage() }} de {{ $works->lastPage() }} ({{ $works->perPage() }} resultados por página)</p>


        <x-pagination :works="$works" :search="$request->search" />
    </div>

    <div class="col col-lg-8">
        @foreach ($works as $work)
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4" style="max-width: 200px;">
                    @if ($work->cover)
                    <a href="{{ route('works.show',$work->id) }}">
                        <img src="{{ asset('storage/cover/'.$work->cover) }}" class="img-fluid rounded-start"
                            alt="Cover">
                    </a>
                    @else
                    <a href="{{ route('works.show',$work->id) }}">
                        <img src="{{ asset('storage/cover/default.png') }}" class="img-fluid rounded-start" alt="Cover">
                    </a>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}</a></h5>
                        <p class="card-text">{{ $work->type }}</p>
                        <p class="card-text">{{ $work->description }}</p>

                        <form action="{{ route('works.destroy',$work->id) }}" method="POST">

                            <a class="btn btn-info" href="{{ route('works.show',$work->id) }}">Show</a>

                            <a class="btn btn-primary" href="{{ route('works.edit',$work->id) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>


                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="col col-lg-4">
        Filters

        <ul>
            @foreach ($facets as $facet)
            <h2>{{ $facet['fieldName'] }}</h2>
            @foreach ($facet['values'] as $value)

            <li><a href="/works?{{ $facet['field'] }}={{ $value['field'] }}">{{ $value['field'] }}
                    ({{ $value['count'] }})</a></li>

            @endforeach


            @endforeach
        </ul>

    </div>
    <x-pagination :works="$works" :search="$request->search" />
</div>



@endsection
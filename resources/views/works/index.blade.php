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
        <p>Foram encontrados {{ $works->total() }} resultados para a busca "{{ $request->search }}"</p>
        <p>Exibindo página {{ $works->currentPage() }} de {{ $works->lastPage() }}</p>
        <p>Exibindo {{ $works->perPage() }} resultados por página</p>
        <nav aria-label="Navigation">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="{{ $works->previousPageUrl() }}">Anterior</a></li>
                @for ($i = 1; $i <= $works->lastPage(); $i++)
                    <li class="page-item"><a class="page-link" href="{{ $works->url($i) }}">{{ $i }}</a></li>
                    @endfor
                    <li class="page-item"><a class="page-link" href="{{ $works->nextPageUrl() }}">Próxima</a></li>
            </ul>
        </nav>
    </div>

    <div class="col col-lg-8">


        @foreach ($works as $work)


        <div class="card mb-3">
            <div class="row g-0" style="max-width: 540px;">
                <div class="col-md-4">
                    <img src="https://m.media-amazon.com/images/I/61JDKFKVntL._AC_UF1000,1000_QL80_.jpg"
                        class="img-fluid rounded-start" alt="Cover">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $work->name }}</h5>
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

        <nav aria-label="Navigation">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="{{ $works->previousPageUrl() }}">Anterior</a></li>
                @for ($i = 1; $i <= $works->lastPage(); $i++)
                    <li class="page-item"><a class="page-link" href="{{ $works->url($i) }}">{{ $i }}</a></li>
                    @endfor
                    <li class="page-item"><a class="page-link" href="{{ $works->nextPageUrl() }}">Próxima</a></li>
            </ul>
        </nav>

    </div>
    <div class="col col-lg-4">
        Filters
    </div>
</div>



@endsection
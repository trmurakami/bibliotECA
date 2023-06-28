<div class="accordion-item">

    <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ hash('crc32', 'Autores') }}" aria-expanded="true"
            aria-controls="{{ hash('crc32', 'Autores') }}">
            {{ 'Autores' }}
        </button>
    </h2>
    <div id="{{ hash('crc32', 'Autores') }}" class="accordion-collapse collapse"
        data-bs-parent="#{{ hash('crc32', 'Autores') }}">
        <div class="accordion-body">
            <form action="/works" method="get" class="m-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Pesquisar por nome do autor" name="author">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
            <ul>
                @php
                $request_array_imploded = "";
                foreach ($request->all() as $key => $value) {
                if ($key != 'author') {
                $request_array[] = $key . '=' . $value;
                $request_array_imploded = implode('&', $request_array);
                }
                }
                @endphp
                @foreach ($facets as $facet)

                <li class="list-group-item">
                    <a href="/works?author={{ $facet['name'] }}&{{ $request_array_imploded }}">
                        {{ $facet['name'] }} ({{ $facet['works_count'] }})
                    </a>
                </li>



                @endforeach
            </ul>
        </div>
    </div>
</div>
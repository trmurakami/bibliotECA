<div class="card mb-3">
    <div class="card-header">
        <small class="text-body-secondary">{{ $work->type }}</small>
        @if ($work->bookFormat)
        <small class="text-body-secondary">({{ $work->bookFormat }})</small>
        @endif
    </div>
    <div class="row g-0">

        <div class="col-lg-2 d-flex align-items-center justify-content-center">
            <a href="{{ route('works.show',$work->id) }}">
                <x-cover :work="$work" />
            </a>
        </div>
        <div class="col-lg-10">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}
                        ({{ $work->datePublished }})</a></h5>
                <div class="row">
                    <div class="col">

                        @if ($work->doi)
                        <p class="card-text p-0 m-0 lh-sm">
                            <small class="text-body-secondary">
                                DOI:
                                <a href="https://doi.org/{{ $work->doi }}" target="_blank">{{ $work->doi }}</a>
                                <span>
                                    <img height="16"
                                        src="data:image/png;base64,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">
                                </span>
                            </small>
                        </p>
                        @endif
                        @if(isset($work->url))
                        <p class="card-text p-0 m-0">
                            <small class="text-body-secondary">URL:
                                <a href="{{ $work->url }}" target="_blank"><i class="bi bi-link"></i> Acessar o
                                    trabalho</a>
                            </small>
                        </p>
                        @endif
                        @if ($work->author)
                        <div class="card-text p-0 m-0 lh-sm"><small class="text-body-secondary">Autores:</small>
                            <ul>
                                @foreach ($work->author as $author)
                                <li class="list-group-item">
                                    <small class="text-body-secondary">
                                        <a href="/works?author={{ $author['name'] }}">{{ $author['name'] }}</a>
                                    </small>
                                    @if ($author['function'])
                                    <small>({{ $author['function'] }})</small>
                                    @endif
                                    @if (isset($author['id_lattes13']))
                                    <a href="https://lattes.cnpq.br/{{ $author['id_lattes13'] }}" target="_blank">
                                        <img src="data:image/png;base64,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"
                                            height="15" />
                                    </a>
                                    @endif
                                    @if(isset($author['viaf']))
                                    <a href="https://viaf.org/viaf/{{ $author['viaf'] }}" target="_blank">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAUVBMVEX/////vb3/jIz/paX/e3v/7+//hIT/nJz/3t7/ra3/zs7/a2v/EBD/AAD/UlL/GBj/Y2P/1tb/Wlr/ISH/c3P/MTH/lJT/QkL/OTn/Skr/xsa2XZToAAABSklEQVR4AaXSwbKDIAwF0KsYiuhFBRDb///QZ5VWxlVn3nGRzU0yGcSvmlbtOoF+KNXA9O+qWnzZYeSoLdC4aV6Afpg4eYXLEhiwEx/l6Aj0qJnEhN3qcGg5NqhJZjqKxWFjMqjJxgCIK2sN2eE+gcBjxkmT9hZ4klie5jsv4caRxjc4vSZG3HjSaRSaU4ObSGaDInEzKKomg2KpbqgCGidZN9Ir3KgoOD2i7rTXuBH8S/1M1s+6Oz4XUXmRBm/STAxR9UrXp8DkTwBCOsFu6nEZSC7lClIdQRF89VsmS4PjKAC0xcUm25U+2EAnYvosuPgI/ZmgyJC3berqC5NqZlLjLTMtQB9MvcAPQy4BGekBmFj/bSsAVQJrWVVpn3IFxB831NS5zZJzuQE16VOwAnl5MvVmzWRqDS7LQ6lV8NJqr9p26v0IBr/7A25tELJ2ng49AAAAAElFTkSuQmCC"
                                            alt="VIAF" title="VIAF" width="15" height="15" />
                                        @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if ($work->about)
                        <div class="card-text p-0 m-0 lh-sm"><small class="text-body-secondary">Assuntos:</small>
                            <ul>
                                @foreach ($work->about as $about)
                                <li class="list-group-item">
                                    <small class="text-body-secondary">
                                        <a href="/works?about={{ $about['name'] }}">{{ $about['name'] }}</a>
                                    </small>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <small class="text-body-secundary">Imprenta</small>
                            </div>
                            <div class="card-body p-2 m-0">
                                @if (isset($work->publisher))
                                <p class="card-text p-0 m-0">
                                    <small class="text-body-secondary">
                                        Editora: {{ $work->publisher }}
                                    </small>
                                </p>
                                @endif
                                @if (isset($work->sourceOrganization))
                                <p class="card-text p-0 m-0">
                                    <small class="text-body-secondary">
                                        Instituição: {{ $work->sourceOrganization }}
                                    </small>
                                </p>
                                @endif
                                @if (isset($work->inSupportOf))
                                <p class="card-text p-0 m-0">
                                    <small class="text-body-secondary">
                                        Programa de Pós Graduação: {{ $work->inSupportOf }}
                                    </small>
                                </p>
                                @endif
                                @if (isset($work->numberOfPages))
                                <p class="card-text p-0 m-0">
                                    <small class="text-body-secondary">
                                        Número de páginas: {{ $work->numberOfPages }}
                                    </small>
                                </p>
                                @endif
                                @if (isset($work->bookEdition))
                                <p class="card-text p-0 m-0">
                                    <small class="text-body-secondary">
                                        Edição: {{ $work->bookEdition }} ed.
                                    </small>
                                </p>
                                @endif
                                @if (isset($work->isPartOf_name))
                                <p class="fw-light lh-1 fs-6 p-0 m-0">
                                    <small class="text-body-secondary">
                                        {{ $work->isPartOf_name }}
                                    </small>
                                </p>
                                @if (isset($work->issn))
                                <p class="fw-light lh-1 fs-6 p-0 m-0">
                                    <small class="text-body-secondary">
                                        ISSN: {{ $work->issn }}
                                    </small>
                                </p>
                                @endif
                                @if (!empty($work->volumeNumber))
                                <p class="fw-light lh-1 fs-6 p-0 m-0">
                                    <small class="text-body-secondary">
                                        Vol.: {{ $work->volumeNumber }}
                                    </small>
                                </p>
                                @endif
                                @if (!empty($work->issueNumber))
                                <p class="fw-light lh-1 fs-6 p-0 m-0">
                                    <small class="text-body-secondary">
                                        Num.: {{ $work->issueNumber }}
                                    </small>
                                </p>
                                @endif
                                @if (!empty($work->pageStart))
                                <p class="fw-light lh-1 fs-6 p-0 m-0">
                                    <small class="text-body-secondary">
                                        Pág. {{ $work->pageStart }} - {{ $work->pageEnd }}
                                    </small>
                                </p>
                                @endif
                                @endif
                                @if (isset($work->releasedEvent))
                                <p class="card-text">
                                    <small class="text-body-secondary">
                                        Apresentado no evento: {{ $work->releasedEvent }}
                                        @if (!empty($work->volumeNumber))
                                        Vol.: {{ $work->volumeNumber }}
                                        @endif
                                        @if (!empty($work->issueNumber))
                                        , Num.: {{ $work->issueNumber }}
                                        @endif
                                        @if (!empty($work->pageStart))
                                        , p. {{ $work->pageStart }} - {{ $work->pageEnd }}
                                        @endif
                                    </small>
                                    @endif
                                <p class="card-text">{{ $work->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
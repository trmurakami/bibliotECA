<nav aria-label="Navigation">
    <ul class="pagination justify-content-center">
        @if ($people->onFirstPage())
        <li class="page-item"><a class="page-link disabled">Anterior</a>
        </li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $people->previousPageUrl() }}">Anterior</a></li>
        @endif
        @for ($i = 1; $i <= $people->lastPage(); $i++)
            @if ($i == $people->currentPage())
            <li class="page-item"><a class="page-link active" href="{{ $people->url($i) }}">{{ $i }}</a></li>
            @else
            <li class="page-item"><a class="page-link" href="{{ $people->url($i) }}">{{ $i }}</a></li>
            @endif
            @endfor

            @if ($people->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $people->nextPageUrl() }}">Próxima</a></li>
            @else
            <li class="page-item"><a class="page-link disabled">Próxima</a></li>
            @endif
    </ul>
</nav>
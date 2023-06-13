<nav aria-label="Navigation">
    <ul class="pagination justify-content-center">
        @if ($works->onFirstPage())
        <li class="page-item"><a class="page-link disabled">Anterior</a>
        </li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $works->previousPageUrl() }}">Anterior</a></li>
        @endif
        @for ($i = 1; $i <= $works->lastPage(); $i++)
            @if ($i == $works->currentPage())
            <li class="page-item"><a class="page-link active" href="{{ $works->url($i) }}">{{ $i }}</a></li>
            @else
            <li class="page-item"><a class="page-link" href="{{ $works->url($i) }}">{{ $i }}</a></li>
            @endif
            @endfor

            @if ($works->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $works->nextPageUrl() }}">Próxima</a></li>
            @else
            <li class="page-item"><a class="page-link disabled">Próxima</a></li>
            @endif
    </ul>
</nav>
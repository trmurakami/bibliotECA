<nav aria-label="Navigation">
    <ul class="pagination justify-content-center">
        @if ($things->onFirstPage())
        <li class="page-item"><a class="page-link disabled">Anterior</a>
        </li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $things->previousPageUrl() }}">Anterior</a></li>
        @endif
        @for ($i = 1; $i <= $things->lastPage(); $i++)
            @if ($i == $things->currentPage())
            <li class="page-item"><a class="page-link active" href="{{ $things->url($i) }}">{{ $i }}</a></li>
            @else
            <li class="page-item"><a class="page-link" href="{{ $things->url($i) }}">{{ $i }}</a></li>
            @endif
            @endfor

            @if ($things->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $things->nextPageUrl() }}">Próxima</a></li>
            @else
            <li class="page-item"><a class="page-link disabled">Próxima</a></li>
            @endif
    </ul>
</nav>
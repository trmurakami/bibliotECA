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
            <ul>

                @foreach ($facets as $facet)

                <li>
                    <a href="/works?author={{ $facet['name'] }}">
                        {{ $facet['name'] }} ({{ $facet['works_count'] }})
                    </a>
                </li>



                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="accordion-item">
    @foreach ($facets as $facet)
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ $facet['fieldName'] }}" aria-expanded="true" aria-controls="{{ $facet['fieldName'] }}">
            {{ $facet['fieldName'] }}
        </button>
    </h2>
    <div id="{{ $facet['fieldName'] }}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <ul>
                @foreach ($facet['values'] as $value)
                <li><a href="/works?{{ $facet['field'] }}={{ $value['field'] }}">{{ $value['field'] }}
                        ({{ $value['count'] }})</a></li>

                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
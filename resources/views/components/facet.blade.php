<div class="accordion-item">
    @foreach ($facets as $facet)
    <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ hash('crc32', $fieldName) }}" aria-expanded="true"
            aria-controls="{{ hash('crc32', $fieldName) }}">
            {{ $fieldName }}
        </button>
    </h2>
    <div id="{{ hash('crc32', $fieldName) }}" class="accordion-collapse collapse"
        data-bs-parent="#{{ hash('crc32', $fieldName) }}">
        <div class="accordion-body">
            <ul>
                @foreach ($facet['values'] as $value)



                @foreach ($facet['request'] as $request)

                @php

                $request_array = [];
                if (!empty($request['value'])) {
                $request_array[] = $request['field'] . '=' . $request['value'];
                $request_array = array_unique($request_array);
                }

                @endphp
                @endforeach

                <li class="list-group-item">
                    <a href="/works?{{ $field }}={{ $value['field'] }}&{{ implode('&', $request_array) }}">
                        {{ $value['field'] }} ({{ $value['count'] }})
                    </a>
                </li>



                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
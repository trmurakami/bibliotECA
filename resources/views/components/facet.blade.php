<div>
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
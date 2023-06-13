@foreach ($facets as $facet)

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">{{ $facet->name }}</h5>
        <p class="card-text">
            @foreach ($facet->values as $value)
            <a href="{{ route('works.facet', ['facet' => $facet->id, 'value' => $value->id]) }}"
                class="badge bg-secondary">{{ $value->name }}</a>
            @endforeach
        </p>
    </div>
</div>


@endforeach
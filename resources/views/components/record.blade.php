<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4" style="max-width: 200px;">
            @if ($work->cover)
            <a href="{{ route('works.show',$work->id) }}">
                <img src="{{ asset('storage/cover/'.$work->cover) }}" class="img-fluid rounded-start" alt="Cover">
            </a>
            @else
            <a href="{{ route('works.show',$work->id) }}">
                <img src="{{ asset('storage/cover/default.png') }}" class="img-fluid rounded-start" alt="Cover">
            </a>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}
                        ({{ $work->datePublished }})</a></h5>
                <p class="card-text"><small class="text-body-secondary">{{ $work->type }}</small></p>
                <p class="card-text">{{ $work->description }}</p>
            </div>
        </div>
    </div>
</div>
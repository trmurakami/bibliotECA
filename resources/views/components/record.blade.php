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
                <h5 class="card-title"><a href="{{ route('works.show',$work->id) }}">{{ $work->name }}</a></h5>
                <p class="card-text">{{ $work->type }}</p>
                <p class="card-text">{{ $work->description }}</p>

                <form action="{{ route('works.destroy',$work->id) }}" method="POST">

                    <a class="btn btn-info" href="{{ route('works.show',$work->id) }}">Show</a>

                    <a class="btn btn-primary" href="{{ route('works.edit',$work->id) }}">Edit</a>

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>


                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
            </div>
        </div>
    </div>
</div>
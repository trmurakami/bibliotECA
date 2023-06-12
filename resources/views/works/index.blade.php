@extends('works.layout')

@section('content')

<div class="container">
    <div class="row">

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        <div class="col col-lg-8">



            @foreach ($works as $work)


            <div class="card mb-3">
                <div class="row g-0" style="max-width: 540px;">
                    <div class="col-md-4">
                        <img src="https://m.media-amazon.com/images/I/61JDKFKVntL._AC_UF1000,1000_QL80_.jpg"
                            class="img-fluid rounded-start" alt="Cover">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $work->name }}</h5>
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


            @endforeach

        </div>
        <div class="col col-lg-4">
            Filters
        </div>
    </div>
</div>


@endsection
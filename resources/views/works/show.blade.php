@extends('layouts.layout')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Show work</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('works.index') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $work->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Type:</strong>
            {{ $work->type }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Description:</strong>
            {{ $work->description }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Ano de publicação:</strong>
            {{ $work->datePublished }}
        </div>
    </div>
    <form action="{{ route('works.destroy',$work->id) }}" method="POST">

        <a class="btn btn-info" href="{{ route('works.show',$work->id) }}">Show</a>

        <a class="btn btn-primary" href="{{ route('works.edit',$work->id) }}">Edit</a>

        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>
@endsection
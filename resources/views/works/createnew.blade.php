@extends('layouts.layout')

@section('title', 'BibliotECA - Editor de registros')

@section('content')
<div id="editor">
    <editor></editor>
</div>

<script src="{{ mix('js/editor.js') }}"></script>
@endsection
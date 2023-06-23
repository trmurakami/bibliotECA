@extends('layouts.layout')

@section('title', 'BibliotECA - Upload')

@section('content')

<h4 class="mt-3">Upload de Arquivo CSV</h4>

@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
<div style="color: red;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('upload.upload') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo CSV - São aceitos os campos:
            id,
            about,
            abstract,
            actor,
            albumProductionType,
            albumReleaseType,
            author,
            byartist,
            releasedEvent,
            countryOfOrigin,
            datePublished,
            description,
            director,
            doi,
            duration,
            embedUrl,
            endDate,
            inAlbum,
            inLanguage,
            inPlaylist,
            isaccessibleforfree,
            isbn,
            isPartOf_citation,
            isPartOf_name,
            isrcCode,
            issn,
            issueNumber,
            musicalinstruments,
            musicby,
            name,
            notesPrivate,
            notesPublic,
            numTracks,
            oaimetadataformat,
            oaipmh,
            pageEnd,
            pageStart,
            pagination,
            productionCompany,
            recordingOf,
            startDate,
            subtitleLanguage,
            titleEIDR,
            track,
            translationOfWork,
            thumbnailUrl,
            type,
            uploadDate,
            videoFrameSize,
            videoQuality,
            volumeNumber</label>
        <input class="form-control" type="file" for="file" accept=".csv, .txt" name="file">
        <input type="text" name="new_filename" value="ebooks.csv" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

<hr />

<h4 class="mt-3">Upload de Arquivo XML do Lattes</h4>

<form method="POST" action="{{ route('lattes.processXML') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo XML do Lattes</label>
        <input class="form-control" type="file" for="file" accept=".xml" name="file">
        <input type="text" name="new_filename" value="curriculo.xml" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

<h4 class="mt-3">Upload de Arquivo MARC</h4>
<form method="POST" action="{{ route('marc.processMARC') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="file" class="form-label">Arquivo MARC</label>
        <input class="form-control" type="file" for="file" accept=".mrc" name="file">
        <input type="text" name="new_filename" value="marc.mrc" hidden>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

@endsection
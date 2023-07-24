@extends('layouts.layout')

@section('title', 'BibliotECA - Upload')

@section('content')
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Upload</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
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
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="mt-3">Upload de Arquivo XML do Lattes</h4>
                    <form method="POST" action="{{ route('lattes.processXML') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Arquivo XML do Lattes</label>
                            <input class="form-control" type="file" for="file" accept=".xml" name="file">
                            <input type="text" name="new_filename" value="curriculo.xml" hidden>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="universidade">Universidade</span>
                            <input type="text" class="form-control" aria-label="Universidade"
                                aria-describedby="universidade" name="universidade">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="ppg">Nome do PPG</span>
                            <input type="text" class="form-control" aria-label="ppg" aria-describedby="ppg" name="ppg">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="mt-3">Upload de Arquivo MARC</h4>
                    <form method="POST" action="{{ route('marc.processMARC') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Arquivo MARC</label>
                            <p>Tamanho máximo do arquivo {{ (int)(ini_get('post_max_size')) }} MB</p>
                            <input class="form-control" type="file" for="file" accept=".mrc" name="file">
                            <input type="text" name="new_filename" value="marc.mrc" hidden>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="mt-3">Upload de Arquivo de Teses da CAPES</h4>
                    <form method="POST" action="{{ route('upload.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Arquivo TSV da CAPES</label>
                            <p>Tamanho máximo do arquivo {{ (int)(ini_get('post_max_size')) }} MB</p>
                            <input class="form-control" type="file" for="file" accept=".tsv" name="file">
                            <input type="text" name="new_filename" value="thesis.csv" hidden>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="mt-3">Upload de Arquivo de Classificação</h4>
                    <form method="POST" action="/classificador/uploadTSVTreinamento" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Arquivo de Classificação em TSV - Formato: String \t
                                label</label>
                            <p>Tamanho máximo do arquivo {{ (int)(ini_get('post_max_size')) }} MB</p>
                            <input class="form-control" type="file" for="file" accept=".tsv" name="file">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
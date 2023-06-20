<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $casts = [
        'about' => 'array',
        'actor' => 'array',
        'author' => 'array',
        'authors_array' => 'array',
        'byartist' => 'array',
        'character' => 'array',
        'countryOfOrigin' => 'array',
        'director' => 'array',
        'inAlbum' => 'array',
        'inlanguage' => 'array',
        'inPlaylist' => 'array',
        'isbn' => 'array',
        'musicalinstruments' => 'array',
        'musicby' => 'array',
        'productionCompany' => 'array',
        'publisher' => 'array',
        'subtitleLanguage' => 'array',
        'translator' => 'array'
    ];

    protected $fillable = [
        'id',
        'about',
        'abstract',
        'actor',
        'albumProductionType',
        'albumReleaseType',
        'author',
        'authors_array',
        'byartist',
        'releasedEvent',
        'countryOfOrigin',
        'datePublished',
        'description',
        'director',
        'doi',
        'duration',
        'embedUrl',
        'endDate',
        'inAlbum',
        'inlanguage',
        'inPlaylist',
        'isaccessibleforfree',
        'isbn',
        'isPartOf_citation',
        'isPartOf_name',
        'isrcCode',
        'issn',
        'issueNumber',
        'musicalinstruments',
        'musicby',
        'name',
        'notesPrivate',
        'notesPublic',
        'numTracks',
        'oaimetadataformat',
        'oaipmh',
        'pageEnd',
        'pageStart',
        'pagination',
        'productionCompany',
        'recordingOf',
        'startDate',
        'subtitleLanguage',
        'titleEIDR',
        'track',
        'translationOfWork',
        'thumbnailUrl',
        'type',
        'uploadDate',
        'videoFrameSize',
        'videoQuality',
        'volumeNumber'
    ];
}
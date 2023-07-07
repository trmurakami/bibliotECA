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
        'byartist' => 'array',
        'character' => 'array',
        'director' => 'array',
        'inAlbum' => 'array',
        'inPlaylist' => 'array',
        'isbn' => 'array',
        'musicalinstruments' => 'array',
        'musicby' => 'array',
        'productionCompany' => 'array',
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
        'base64Image',
        'byartist',
        'bookEdition',
        'bookFormat',
        'countryOfOrigin',
        'datePublished',
        'description',
        'director',
        'doi',
        'duration',
        'embedUrl',
        'endDate',
        'inAlbum',
        'inLanguage',
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
        'publisher',
        'recordingOf',
        'releasedEvent',
        'sourceOrganization',
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
        'volumeNumber',
        'url'
    ];

    protected $with = ['authors'];

    public function authors()
    {
        return $this->belongsToMany(Thing::class, 'thing_work')->withPivot('function')->withTimestamps();
    }

    public function abouts()
    {
        return $this->belongsToMany(About::class, 'about_work')->withTimestamps();
    }

}
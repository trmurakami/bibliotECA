<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->jsonb('about')->nullable();
            $table->text('abstract')->nullable();
            $table->jsonb('actor')->nullable();
            $table->string('albumProductionType')->nullable();
            $table->string('albumReleaseType')->nullable();
            $table->string('alternateName')->nullable();
            $table->jsonb('author')->nullable();
            $table->jsonb('award')->nullable(); // not included
            $table->longText('base64Image')->nullable();
            $table->string('bookEdition')->nullable();
            $table->string('bookFormat')->nullable();
            $table->jsonb('byartist')->nullable();
            $table->jsonb('citation')->nullable(); // not included
            $table->jsonb('character')->nullable();
            $table->jsonb('contributor')->nullable(); // not included
            $table->jsonb('copyrightHolder')->nullable(); // not included
            $table->string('copyrightYear')->nullable();
            $table->string('countryOfOrigin')->nullable();
            $table->longText('coverimage')->nullable();
            $table->string('dateCreated')->nullable(); // not included
            $table->string('datePublished')->nullable();
            $table->longText('description')->nullable();
            $table->jsonb('director')->nullable();
            $table->string('doi')->nullable();
            $table->string('duration')->nullable();
            $table->jsonb('editor')->nullable(); // not included
            $table->text('embedUrl')->nullable();
            $table->string('endDate')->nullable();
            $table->jsonb('funder')->nullable(); // not included
            $table->jsonb('genre')->nullable(); // not included
            $table->jsonb('hasPart')->nullable(); // not included
            $table->jsonb('identifier')->nullable(); // not included
            $table->string('image')->nullable();
            $table->jsonb('inAlbum')->nullable();
            $table->string('inLanguage')->nullable();
            $table->jsonb('inPlaylist')->nullable(); // not included
            $table->boolean('isaccessibleforfree')->nullable();
            $table->jsonb('isBasedOn')->nullable(); // not included
            $table->jsonb('isbn')->nullable();
            $table->jsonb('isPartOf')->nullable(); // not included
            $table->string('isPartOf_name')->nullable();
            $table->string('isPartOf_citation')->nullable();
            $table->string('isrcCode')->nullable();
            $table->string('issn')->nullable();
            $table->string('issueNumber')->nullable();
            $table->text('license')->nullable();
            $table->jsonb('maintainer')->nullable(); // not included
            $table->jsonb('material')->nullable(); // not included
            $table->jsonb('mentions')->nullable(); // not included
            $table->jsonb('musicalinstruments')->nullable();
            $table->jsonb('musicby')->nullable();
            $table->string('name')->nullable();
            $table->text('notesPublic')->nullable();
            $table->text('notesPrivate')->nullable();
            $table->string('numberOfPages')->nullable();
            $table->string('numTracks')->nullable();
            $table->string('oaimetadataformat')->nullable();
            $table->string('oaipmh')->nullable();
            $table->string('oaiset')->nullable();
            $table->string('pageEnd')->nullable();
            $table->string('pageStart')->nullable();
            $table->string('pagination')->nullable();
            $table->jsonb('producer')->nullable(); // not included
            $table->jsonb('productionCompany')->nullable();
            $table->string('publisher')->nullable();
            $table->string('recordingOf')->nullable();
            $table->string('releasedEvent')->nullable();
            $table->string('startDate')->nullable();
            $table->jsonb('size')->nullable(); // not included
            $table->string('sourceOrganization')->nullable();
            $table->jsonb('subjectOf')->nullable(); // not included
            $table->jsonb('subtitleLanguage')->nullable();
            $table->string('temporalCoverage')->nullable(); // not included
            $table->string('titleEIDR')->nullable();
            $table->string('thumbnailUrl')->nullable();
            $table->jsonb('track')->nullable(); // not included
            $table->string('translationOfWork')->nullable();
            $table->jsonb('translator')->nullable();
            $table->string('things')->nullable();
            $table->string('type');
            $table->string('uploadDate')->nullable();
            $table->string('url')->nullable();
            $table->string('version')->nullable(); // not included
            $table->string('videoFrameSize')->nullable();
            $table->string('videoQuality')->nullable();
            $table->string('volumeNumber')->nullable();
            $table->jsonb('workTranslation')->nullable(); // not included
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
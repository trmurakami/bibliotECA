<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TNTSearch Index Storage Path
    |--------------------------------------------------------------------------
    |
    | This option defines the directory where the search index files will be
    | stored by TNTSearch. Make sure this path is writable by your web server.
    |
    */

    'storage' => storage_path('app/tntsearch'),

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Database Connection
    |--------------------------------------------------------------------------
    |
    | This option defines the database connection to be used by TNTSearch.
    | You can specify a connection name defined in your database configuration.
    |
    */

    'connection' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Database Index Table
    |--------------------------------------------------------------------------
    |
    | This option defines the name of the table where the search index
    | will be stored. By default, it uses the "search_index" table.
    |
    */

    'index_table' => 'search_index',

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Searchable Models
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the models that should be indexed
    | and searchable by TNTSearch. You can define the Eloquent models
    | along with the fields that should be included in the index.
    |
    */

    'searchable_models' => [
        'App\Models\StringClassifier' => ['content', 'label'],
    ],

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Stemmer Language
    |--------------------------------------------------------------------------
    |
    | This option defines the language to be used by TNTSearch's stemmer.
    | You can set it to 'null' to disable stemming or specify a language code.
    | Supported languages: danish, dutch, english, finnish, french, german,
    | hungarian, italian, norwegian, portuguese, romanian, russian, spanish,
    | swedish, turkish.
    |
    */

    'stemmer' => 'english',

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Fuzziness
    |--------------------------------------------------------------------------
    |
    | This option determines the level of fuzziness for search queries.
    | Set it to a value between 0 and 1. Higher values allow more
    | leniency in matching search terms.
    |
    */

    'fuzziness' => env('TNTSEARCH_FUZZINESS', 0),

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Minimum Search Length
    |--------------------------------------------------------------------------
    |
    | This option sets the minimum length of search terms to be considered
    | in the search queries. Terms shorter than this length will be ignored.
    |
    */

    'min_search_length' => 3,

    /*
    |--------------------------------------------------------------------------
    | TNTSearch Pagination
    |--------------------------------------------------------------------------
    |
    | This option defines the number of search results to be displayed
    | per page when using the TNTSearch paginator.
    |
    */

    'pagination' => [
        'per_page' => 10,
    ],
];
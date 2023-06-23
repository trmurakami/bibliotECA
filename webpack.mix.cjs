// webpack.mix.js

const mix = require('laravel-mix');

mix.js('resources/js/editor.js', 'public/js').vue();
mix.js('resources/js/cutter.js', 'public/js').vue();
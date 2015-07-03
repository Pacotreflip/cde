var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less')
        .copy('vendor/bower_components/bootstrap/fonts', 'public/fonts/bootstrap')
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/fontawesome')
        .copy('vendor/bower_components/roboto-fontface/fonts', 'public/fonts/roboto')
        .scripts([
            'jquery/dist/jquery.js',
            'bootstrap/dist/js/bootstrap.js',
            'jasny-bootstrap/js/fileinput.js',
            'jquery.inputmask/dist/jquery.inputmask.bundle.js',
            'modernizr/modernizr.js',
            'pickadate/lib/picker.js',
            'pickadate/lib/picker.date.js',
            'pickadate/lib/picker.time.js',
            'pickadate/lib/translations/es_ES.js',
            'typeahead.js/dist/typeahead.jquery.js',
        ], 'public/js/app.js', 'vendor/bower_components')
});

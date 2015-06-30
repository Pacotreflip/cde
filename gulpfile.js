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
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/font-awesome')
        .scripts([
            'jquery/dist/jquery.js',
            'bootstrap/dist/js/bootstrap.js',
            'bootstrap-datepicker/js/bootstrap-datepicker.js',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js',
            'typeahead.js/dist/typeahead.jquery.js',
        ], 'public/js/app.js', 'vendor/bower_components')
});

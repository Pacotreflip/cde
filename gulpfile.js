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
    mix.copy('vendor/bower_components/font-awesome/less', 'resources/assets/less/font-awesome')
        .copy('vendor/bower_components/bootstrap-datepicker/less/datepicker3.less', 'resources/assets/less/bootstrap-datepicker/datepicker3.less')
        .copy('vendor/bower_components/typeahead.js-bootstrap3.less/typeahead.less', 'resources/assets/less/typeahead/typeahead.less')
        .copy('vendor/bower_components/bootstrap/fonts', 'public/fonts/bootstrap')
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/font-awesome')
        .less('app.less')
        .scripts([
            'jquery/dist/jquery.js',
            'bootstrap/dist/js/bootstrap.js',
            'bootstrap-datepicker/js/bootstrap-datepicker.js',
            'bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js',
            'typeahead.js/dist/typeahead.jquery.js',
        ],
        'public/js/app.js', 'vendor/bower_components')
});

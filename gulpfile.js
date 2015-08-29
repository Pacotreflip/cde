var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/font-awesome')
        .copy('vendor/bower_components/roboto-fontface/fonts', 'public/fonts/roboto')
        .scripts([
            'jquery/dist/jquery.js',
            'bootstrap-sass/assets/javascripts/bootstrap.js'
        ], 'public/js/app.js', 'vendor/bower_components');
});

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
    mix.less('app.less')
        .copy('vendor/bower_components/roboto-fontface/fonts', 'public/fonts/roboto')
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/font-awesome')
        .copy('node_modules/jstree/src/themes/default/32px.png', 'public/img')
        .copy('node_modules/jstree/src/themes/default/40px.png', 'public/img')
        .copy('node_modules/jstree/src/themes/default/throbber.gif', 'public/img')
        .browserify('app.js');
});

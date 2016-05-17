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
        .copy('node_modules/jstree/src/themes/default/32px.png', 'public/build/css')
        .copy('node_modules/jstree/src/themes/default/40px.png', 'public/build/css')
        .copy('node_modules/jstree/src/themes/default/throbber.gif', 'public/build/css')
        .browserify('app.js');
    mix.sass('app.scss');
    mix.less("tree.less");
    mix.version([
        'css/app.css',
        'css/tree.css'
    ]);
    mix.copy('node_modules/bootstrap-sass/assets/fonts','public/build/fonts');
    mix.copy('vendor/bower_components/jquery-ui/themes/smoothness/images','public/build/css/images');
    mix.copy('node_modules/tablesorter/dist/css/images','public/build/css/images');
    //mix.copy('public/js/app.js','resources/assets/js/');
    mix.copy('vendor/bower_components/jquery-ui/ui/jquery-ui.js','public/js/');
   // mix.copy('vendor/bower_components/jquery-ui/ui/autocomplete.js','resources/assets/js/');
    //mix.scripts(['*.js',"app.js"], "public/js/app.js");
//    mix.task('js', function() 
//    {
//      mix.src('vendor/bower_components/jquery-ui/ui/*.js')
//            .pipe(concatJs('concat.js'))
//            .pipe(uglify())
//            .pipe(mix.dest('public/js'))
//            .pipe(notify("Ha finalizado la task js!"));
//        });
    });

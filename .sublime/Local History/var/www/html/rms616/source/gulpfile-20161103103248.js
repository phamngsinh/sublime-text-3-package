var elixir = require('laravel-elixir');
require('laravel-elixir-del');
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
elixir.config.sourcemaps = false;
elixir(function (mix) {
    mix.copy('bower_components/bootstrap/dist/fonts', 'public/assets/fonts');
    mix.copy('bower_components/fontawesome/fonts', 'public/assets/fonts');
    mix.copy('resources/assets/images', 'public/assets/images');
    mix.copy('resources/assets/fonts', 'public/assets/fonts');
    mix.sass('./Modules/Administrator/Resources/assets/sass/app.sass', 'Modules/Administrator/Resources/assets/css');
    mix.sass([
        'app.scss',
        'main.scss'
    ], 'public/assets/stylesheets');
    mix.styles([
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'bower_components/fontawesome/css/font-awesome.min.css',
        'bower_components/owl-carousel/owl-carousel/owl.carousel.css',
        'public/assets/stylesheets/app.css'
    ], 'public/assets/stylesheets', './');
    mix.del('public/assets/stylesheets/app.css');
    mix.styles([
        'bower_components/bootstrap/dist/css/bootstrap.css',
        'bower_components/fontawesome/css/font-awesome.css',
        'bower_components/bootstrap-toggle/css/bootstrap-toggle.css',
        'Modules/Administrator/Resources/css/sb-admin-2.css',
        'Modules/Administrator/Resources/css/timeline.css',
        'Modules/Administrator/Resources/assets/css/app.css'
    ], 'public/assets/stylesheets/admin.css', './');
    mix.scripts([
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/bootstrap-toggle/js/bootstrap-toggle.js',
        'bower_components/Chart.js/Chart.js',
        'bower_components/metisMenu/dist/metisMenu.js',
        'bower_components/owl-carousel/owl-carousel/owl.carousel.min.js',
        'bower_components/imgLiquid/js/imgLiquid-min.js',
        'Modules/Administrator/Resources/js/sb-admin-2.js',
        'Modules/Administrator/Resources/js/frontend.js',
        'resources/assets/js/app.js',
    ], 'public/assets/scripts/admin.js', './');
    mix.scripts([
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/owl-carousel/owl-carousel/owl.carousel.min.js',
        'bower_components/imgLiquid/js/imgLiquid-min.js',
        'bower_components/jquery-validation/dist/jquery.validate.min.js',
        'bower_components/jquery-validation/dist/additional-methods.min.js',
        'resources/assets/js/app.js',
    ], 'public/assets/scripts/app.js', './');


    mix.styles([
        'resources/assets/css/main.css',
    ], 'public/assets/stylesheets/main.css', './');

    mix.scripts([
        'resources/assets/js/helper.js',
    ], 'public/assets/scripts/helper.js', './');

    mix.scripts([
        'Resources/assets/js/pages/page-client.js'
    ], 'public/assets/scripts/pages/page-client.js', './');

});

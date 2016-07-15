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

var paths = {
	'jquery' 	              : './bower_components/jquery/',
	'bootstrap'	              : './bower_components/bootstrap/',
	'angular'                 : './bower_components/angular/',
}

elixir(function(mix) {
    mix.sass('app.scss')
    	.copy(paths.bootstrap + 'dist/css/bootstrap.css', 'public/css/lib')
        .copy(paths.bootstrap + 'dist/fonts/**', 'public/css/fonts')
        .copy(paths.bootstrap + 'dist/js/bootstrap.js', 'public/js/lib')
        .copy(paths.jquery + 'dist/jquery.js', 'public/js/lib')
        .copy(paths.angular + 'angular.js', 'public/js/lib')
        .scripts('app.js')
});

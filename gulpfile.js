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
     mix.sass([
        	'../../../bower_components/Materialize/sass/materialize.scss',
     		'app.scss'
     	])
        .scripts([
        	'../../../bower_components/jquery/dist/jquery.js',
        	'../../../bower_components/Materialize/dist/js/materialize.js',
        	'../../../bower_components/vue/dist/vue.js',
        	'../../../bower_components/vue-resource/dist/vue-resource.js',
            '../../../node_modules/card/lib/js/card.js',
        	'app.js'
    	])
    	.copy('bower_components/Materialize/dist/font', 'public/font');

});

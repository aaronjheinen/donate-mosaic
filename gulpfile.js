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
            '../../../bower_components/tooltipster/css/tooltipster.css',
            '../../../bower_components/tooltipster/css/themes/*.css',
            '../../../bower_components/jquery-minicolors/jquery.minicolors.css',
            '../../../bower_components/fontawesome/scss/font-awesome.scss',
            '../../../bower_components/tooltipster/css/themes/*.css',
            '../css/content-tools.min.css',
     		'app.scss'
     	])
        .scripts([
        	'../../../bower_components/jquery/dist/jquery.js',
            '../../../bower_components/tooltipster/js/jquery.tooltipster.js',
        	'../../../bower_components/Materialize/dist/js/materialize.js',
        	'../../../bower_components/vue/dist/vue.js',
        	'../../../bower_components/vue-resource/dist/vue-resource.js',
            '../../../bower_components/jquery-minicolors/jquery.minicolors.js',
            '../../../node_modules/card/lib/js/card.js',
            'vendor/html2canvas.js',
            'vendor/content-tools.js',
        	'app.js'
    	])
    	.copy('bower_components/Materialize/dist/font', 'public/font')
        .copy('bower_components/fontawesome/fonts', 'public/fonts')
        .copy('resources/assets/images', 'public/css/img')
        .copy('resources/assets/fonts/icons.woff', 'public/css');

});

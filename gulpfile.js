var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

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
    // compile sass
    mix.sass('app.scss');
    // compile js
    mix.browserify('main.js');
    mix.copy('resources/assets/js/vendor.js', 'public/js/vendor.js');
    mix.copy('resources/assets/js/vendors/slim.jquery.min.js', 'public/js/slim.js');
    // move fonts to public folder
    mix.copy('node_modules/font-awesome/fonts', 'public/build/fonts');
    mix.version(['css/app.css', 'js/main.js']);
});

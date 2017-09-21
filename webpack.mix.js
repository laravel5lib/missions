let mix = require('laravel-mix');
let Path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({
    processCssUrls: false
});

mix.webpackConfig({
    resolve: {
        modules:[
            Path.resolve(__dirname), Path.resolve(__dirname, "node_modules")
        ],
        alias: {
            "TweenLite": Path.resolve('node_modules', 'gsap/src/uncompressed/TweenLite.js'),
            "TweenMax": Path.resolve('node_modules', 'gsap/src/uncompressed/TweenMax.js'),
            "TimelineLite": Path.resolve('node_modules', 'gsap/src/uncompressed/TimelineLite.js'),
            "TimelineMax": Path.resolve('node_modules', 'gsap/src/uncompressed/TimelineMax.js'),
            "ScrollMagic": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/ScrollMagic.js'),
            "animation.gsap": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js'),
            "debug.addIndicators": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/debug.addIndicators.js'),
        },
    },
});

mix.js('./resources/assets/js/main.js', 'public/js').version();
mix.sass('resources/assets/sass/app.scss', 'public/css').version();

mix.copy('resources/assets/js/vendors/slim.jquery.min.js', 'public/js/slim.js');
mix.copy('resources/assets/js/vendors/slim.commonjs.js', 'public/js/slim.commonjs.js');
mix.copy('resources/assets/js/vendors/slim.min.css', 'public/css/slim.css');
mix.copy('node_modules/font-awesome/fonts', 'public/fonts');

mix.browserSync({
    proxy: 'missions.dev'
});
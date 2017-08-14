var webpack = require('webpack');
var Path = require('path');

Elixir.webpack.mergeConfig({
    module: {
        resolve: {
            alias: {
                'images': Path.resolve(__dirname, './images'),
                // "TweenLite": Path.resolve('node_modules', 'gsap/src/uncompressed/TweenLite.js'),
                // "TweenMax": Path.resolve('node_modules', 'gsap/src/uncompressed/TweenMax.js'),
                // "TimelineLite": Path.resolve('node_modules', 'gsap/src/uncompressed/TimelineLite.js'),
                // "TimelineMax": Path.resolve('node_modules', 'gsap/src/uncompressed/TimelineMax.js'),
                // "ScrollMagic": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/ScrollMagic.js'),
                // "animation.gsap": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js'),
                // "debug.addIndicators": Path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/debug.addIndicators.js')
            }
        },
        externals: {
            // 'TweenLite': 'TweenLite',
            // 'TweenMax': 'TweenMax',
            // 'TimelineMax': 'TimelineMax',
        },
        preLoaders: [{
            test: /\.json$/,
            loader: 'json'
        }],
        loaders: [
            {
                test: /\.css$/,
                loader: 'style!css!'
            },
            {
                test: /\.scss$/,
                loaders: ["style-loader", "css-loader", "sass-loader"]
            },
            {
                test: /\.png$/,
                loaders: ["html-loader"]
            }],
        plugins: [
            new webpack.optimize.UglifyJsPlugin({
                comments: false,
            }),
            // new webpack.DefinePlugin({
            //     'process.env': {
            //         NODE_ENV: '"production"'
            //     }
            // }),
            // // minify with dead-code elimination
            // new webpack.optimize.UglifyJsPlugin({
            //     compress: {
            //         warnings: false
            //     }
            // }),
            // // Webpack 1 only - optimize module ids by occurrence count
            // new webpack.optimize.OccurrenceOrderPlugin()
        ]

    }
});
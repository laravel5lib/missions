var webpack = require('webpack');
var Path = require('path');

module.exports = {
    module: {
        resolve: {
            alias: {
                'images': Path.resolve(__dirname, './images'),
            }
        },
        externals: {
            // 'TweenLite': 'TweenLite',
            // 'TweenMax': 'TweenMax',
            // 'TimelineMax': 'TimelineMax',
        },
        preLoaders: [
            {
                test: /\.json$/,
                loader: 'json'
            }
        ],
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
            }
        ],
        plugins: [
            new webpack.optimize.UglifyJsPlugin({
                comments: false,
            }),
        ]

    }
};

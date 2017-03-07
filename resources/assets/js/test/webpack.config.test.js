/**
 * Created by jerezb on 2017-02-23.
 */
const webpack = require('webpack'); //to access built-in plugins
const path = require('path');

module.exports = {
    module: {
        loaders: [
            {
                test: /\.(jpe?g|png|gif|svg)$/i,
                loader: 'ignore-loader'
            },
            {
                test: /\.css$/,
                loader: 'style!css!'
            },
            {
                test: /\.scss$/,
                loaders: ['vue-style-loader', 'css-loader', 'sass-loader']
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
        ]
    },
    plugins: [
        // new webpack.o
    ]
}
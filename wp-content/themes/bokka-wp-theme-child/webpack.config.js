var webpack = require('webpack');
var path = require('path');
module.exports = {

    entry: {
        initialize: "./assets/src/js/initialize.js",
        depend: "./assets/src/js/depend.js",
        common: [
            'lodash',
            'backbone'
        ]
    },
    output: {
        path: __dirname + "/assets/build/js/",
        filename: "[name].min.js"
    },
    module: {
        loaders: [ { test: /\.handlebars$/, loader: "handlebars-loader" } ]
    },
    resolve: {
        // you can now require('file') instead of require('file.coffee')
        extensions: ['', '.html', '.js', '.json', '.coffee'],

        modulesDirectories: [
            path.join(__dirname, "node_modules"),
        ],

        alias: {
            'underscore': 'lodash'
        }

    },

    plugins: [
        new webpack.ProvidePlugin({
            _               : 'lodash',
            backbone        : 'backbone',
        }),
        new webpack.optimize.CommonsChunkPlugin('common', 'common.js'),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        })
    ]

}


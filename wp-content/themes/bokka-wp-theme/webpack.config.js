var webpack = require('webpack');


module.exports = {
    entry: {
        initialize: "./assets/src/js/initialize.js",
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
        loaders: [ {
            test: /\.html$/,
            loader: 'mustache'
            // loader: 'mustache?minify'
            // loader: 'mustache?{ minify: { removeComments: false } }'
            // loader: 'mustache?noShortcut'
        } ]

    },
    resolve: {
      // you can now require('file') instead of require('file.coffee')
      extensions: ['', '.html', '.js', '.json', '.coffee'] 
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


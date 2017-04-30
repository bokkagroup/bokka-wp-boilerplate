var webpack = require('webpack');
var path = require('path');
module.exports = {

    entry: {
        initialize: "./assets/src/js/initialize.js",
       // depend: "./assets/src/js/depend.js",
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
        loaders: [ 
            { test: /\.handlebars$/, loader: "handlebars-loader" },
            { test: /\.modernizrrc$/, loader: "modernizr" }
        ]
    },
    resolve: {
        // you can now require('file') instead of require('file.coffee')
        extensions: ['', '.html', '.js', '.json', '.coffee'],

        modulesDirectories: [
            path.join(__dirname, "node_modules"),
        ],

        alias: {
            modernizr$: path.resolve(__dirname, ".modernizrrc"),
            'underscore': 'lodash',
            jquery: "jquery/src/jquery"
        }

    },

    plugins: [
        new webpack.ProvidePlugin({
            $               : 'jquery',
            jQuery          : 'jquery',
            _               : 'lodash',
            backbone        : 'backbone',
        }),
        new webpack.optimize.CommonsChunkPlugin('common', 'common.js'),
    ]

}


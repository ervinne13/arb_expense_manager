const mix = require('laravel-mix');

require('dotenv').config()
const webpack = require('webpack')

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': __dirname + '/resources'
        }
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                MIX_OAUTH_CLIENT_ID: JSON.stringify(process.env.MIX_OAUTH_CLIENT_ID || ''),
                MIX_OAUTH_CLIENT_SECRET: JSON.stringify(process.env.MIX_OAUTH_CLIENT_SECRET || '')
            }
        })
    ]
});

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'inline-source-map',
        stats: {
            children: true,
        }
    })
}

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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

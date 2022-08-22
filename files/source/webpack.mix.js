const mix = require('laravel-mix');

/* {{@snippet:header}} */

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

 mix.webpackConfig({
    resolve: {
        alias: {
           jQuery: 'jquery',
        },
    },
    stats: {
        children: true,
    },
});

mix.vue({version:2});

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

/* {{@snippet:task}} */

if (mix.inProduction()) {
    mix.version();
}

/* {{@snippet:footer}} */
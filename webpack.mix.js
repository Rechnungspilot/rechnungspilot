const mix = require('laravel-mix');

require('laravel-mix-tailwind');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .extract([
        'bootstrap',
        'highcharts',
        'luxon',
        'moment',
        'vue-datetime',
        'vue-multiselect',
        'weekstart',
    ]);

mix.sass('resources/assets/sass/d15r.scss', 'public/css')
    .tailwind();


if (mix.inProduction()) {
    mix.version();
}

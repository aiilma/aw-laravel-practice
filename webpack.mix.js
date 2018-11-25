const mix = require('laravel-mix');

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
    .js('resources/js/packets/user/backgrounds_load.js', 'public/js')
    .js('resources/js/packets/compositions/compositions_listing.js', 'public/js')
    .js('resources/js/packets/compositions/compositions_form.js', 'public/js')
    .js('resources/js/packets/user/orders/order_controls.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
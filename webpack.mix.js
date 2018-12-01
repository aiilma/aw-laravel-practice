let mix = require('laravel-mix');

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


mix.autoload({
   'jquery': ['jQuery', '$'],
});

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/packets/user/backgrounds_load.js', 'public/js')
   .js('resources/assets/js/packets/compositions/compositions_listing.js', 'public/js')
   .js('resources/assets/js/packets/compositions/compositions_form.js', 'public/js')
   .js('resources/assets/js/packets/user/orders/order_controls.js', 'public/js')
   .js('resources/assets/js/packets/payments/pg_transfer.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
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

mix
    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/jquery.dataTables.min.js',
            'resources/js/front/jquery.sparkline.min.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/home.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/datatables.min.css',
            'resources/css/front/home.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/home.css'
    )
    // End Home



    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/jquery.dataTables.min.js',
            //'resources/js/front/slick.min.js',
            'resources/js/front/progressbar.min.js',
            'resources/js/front/coin.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/coin-mixed.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/datatables.min.css',
            'resources/css/front/coin.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/coin-mixed.css'
    )
    // End Coin


    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/entity-index.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/coins.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/entity-index.css'
    )
    // End Entites Index
    //




    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/wallet.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/wallet.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/wallet.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/wallet.css'
    )
    // End Wallet




    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/exchange.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/exchange.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/exchange.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/exchange.css'
    )
    // End Exchange
    //

    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/mining_company.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/mining_company.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/mining_company.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/mining_company.css'
    )

    // End Mining Company
    //



    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/mining_equipment.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/mining_equipment.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/mining_equipment.css'
    )
    // End Mining Equipment
    //
    //
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/mining_pool.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/mining_pool.css'
    )
    // End Mining Pool
    //



    .scripts(
        [
            'resources/js/jquery-3.5.1.min.js',
            'resources/js/bootstrap.bundle.min.js',
            'resources/js/front/select2.min.js',
            'resources/js/front/select2-fa.js',
        ], 'public/js/atm_map.js'
    )
    .styles([
            'resources/css/bootstrap.min.css',
            'resources/css/front/all.css',
            'resources/css/front/atm_map.css',
            'resources/css/front/select2.min.css'
        ], 'public/css/atm_map.css'
    )
    // End ATM Map



    .sass('resources/sass/app.scss', 'public/css').version();

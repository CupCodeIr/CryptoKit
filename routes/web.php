<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('homepage');


Route::prefix('coins')->name("coins.")->group(function () {
    Route::get('/', 'CoinController@index')->name('index');
    Route::get('/{coin:slug}', 'CoinController@single')->name('single');
});


Route::prefix('exchanges')->name("exchanges.")->group(function () {
    Route::get('/', 'ExchangeController@index')->name('index');
    Route::get('/{exchange:source_id}', 'ExchangeController@single')->name('single');

});

Route::prefix('wallets')->name("wallets.")->group(function () {
    Route::get('/', 'WalletController@index')->name('index');
    Route::get('/{wallet:slug}', 'WalletController@single')->name('single');

});

Route::prefix('mining-companies')->name("mining_companies.")->group(function () {
    Route::get('/', 'MiningCompanyController@index')->name('index');
    Route::get('/{miningcompany:slug}', 'MiningCompanyController@single')->name('single');

});

Route::prefix('mining-pools')->name("mining_pools.")->group(function () {

    Route::get('/', 'MiningPoolController@index')->name('index');
    Route::get('/{miningpool:slug}', 'MiningPoolController@single')->name('single');

});


Route::prefix('mining-equipments')->name("mining_equipments.")->group(function () {
    Route::get('/', 'MiningEquipmentController@index')->name('index');
    Route::get('/{miningequipment:slug}', 'MiningEquipmentController@single')->name('single');

});

Route::prefix('crypto-map')->name("crypto_map.")->group(function () {
    Route::get('/', 'ATMController@index')->name('index');
    Route::middleware('throttle:60')->get('/places', 'ATMController@places')->name('places');
    Route::middleware('throttle:60')->get('/place/{cryptoatm}', 'ATMController@place')->name('place');

});



Route::get('/setCurrency/{currency:code}', 'SetCurrencyController')->name('setCurrency');
Route::get('/search', 'SearchController')->middleware('throttle:60')->name('navSearch');





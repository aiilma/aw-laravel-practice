<?php

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




/**
 * COMPOSITIONS
 */

// compositions list...
Route::get('/compositions', 'Compositions\CompositionController@index')->name('homepage');
Route::get('/compositions/p_{number_page}', 'Compositions\CompositionController@index')->where(['number_page' => '[0-9]+']);

// buying from comp listing page
Route::get('/compositions/p_{number_page}/c_{composition_id}', 'Compositions\CompositionController@showBuyForm')->where(['number_page' => '[0-9]+', 'composition_id' => '[0-9]+']);
Route::post('/compositions/p_{number_page}/c_{composition_id}', 'Compositions\CompositionController@buyComposition')->where(['number_page' => '[0-9]+', 'composition_id' => '[0-9]+']);

// composition showcase
Route::get('/showcase/c_{composition_id}', 'Compositions\ShowcaseController@index')->where(['composition_id' => '[0-9]+']);

// buying from showcase page
Route::post('/showcase/c_{composition_id}', 'Compositions\CompositionController@buyComposition')->where(['composition_id' => '[0-9]+']);




/**
 * ACCOUNT
 */
Route::prefix('/account')->group(function() {

    // .com/account
    Route::get('/', 'Account\AccountHomeController@index')->name('acc-home');

    // .com/account/prod...
    Route::prefix('/prod')->group(function() {
        // .com/account/prod/upload
        Route::get('/upload', 'Account\Production\AccountProductionController@showUploader')->name('acc-prod-showuploader');
        Route::post('/upload', 'Account\Production\AccountProductionController@sendRequest')->name('acc-prod-sendrequest');
        // .com/account/prod/requests
        Route::get('/requests', 'Account\Production\AccountProductionController@showRequests')->name('acc-prod-showrequests');
        Route::get('/list', 'Account\Production\AccountProductionController@showList')->name('acc-prod-showlist');
    });

    // .com/account/orders...
    Route::prefix('/orders')->group(function() {
        Route::get('/', 'Account\Orders\AccountOrderController@showList')->name('acc-orders-showlist');
    });
});




/**
 * PAYMENTS
 */
Route::prefix('/payments')->group(function() {
    // show variants...
    Route::get('/', 'Payments\PaymentController@index');
    Route::post('/', 'Payments\PaymentController@index');

    // yandex money...
    Route::get('/yamoney', 'Payments\PaymentController@showPaymentForm');
    Route::post('/yamoney', 'Payments\PaymentController@sendPaymentRequest');
});
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

Route::get('/', function () {
    return view('common.welcome');
});


// --------------------------------------------------------------------------------





// AUTH GROUP
Auth::routes();

Route::prefix('/account')->group(function() {
    Route::get('/', 'AccountHomeController@index')->name('account.home');

    // .com/account/production
    Route::prefix('/production')->group(function() {
        // Route::get('/', 'Production\ProductionController@index')->name('account.production.home');
        
        // .com/account/production/upload
        Route::get('/upload', 'Production\ProductionController@uploadShow')->name('account.production.upload_show');
        Route::post('/upload', 'Production\ProductionController@uploadSend')->name('account.production.upload_send');
        // .com/account/production/requests
        Route::get('/requests', 'Production\ProductionController@requestsShow')->name('account.production.requests_show');
    });
});


// FIXME: организовать (обернуть) маршруты для аутентифицированных пользователей в группу
// TODO: организовать маршрут (создать ссылку, связать с контроллером и методом) в данной группе
// аутентифицированных пользователей для организации возможности загрузки файлов.
// TODO: написать метод upload, принимающий в качестве параметров аргумент типа UploadRequest

// Account verify
Route::get('/verify/{token}', 'VerifyController@verify')->name('verify');



// --------------------------------------------------------------------------------
// ADMIN GROUP
Route::prefix('admin')->group(function() {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
});
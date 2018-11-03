<?php


/**
 * AUTH
 */
// Authentication Routes...
Route::get('/login', 'Account\Auth\LoginController@showLoginForm')->name('acc-login');
Route::post('/login', 'Account\Auth\LoginController@login');
Route::get('/logout', 'Account\Auth\LoginController@userLogout')->name('acc-logout');
Route::post('/logout', 'Account\Auth\LoginController@userLogout');

// Registration Routes...
if ($options['register'] ?? true) {
    Route::get('/register', 'Account\Auth\RegisterController@showRegistrationForm')->name('acc-register');
    Route::post('/register', 'Account\Auth\RegisterController@register');
}

// Password Reset Routes...
Route::get('/password/reset', 'Account\Auth\ForgotPasswordController@showLinkRequestForm')->name('acc-passwordrequest');
Route::post('/password/email', 'Account\Auth\ForgotPasswordController@sendResetLinkEmail')->name('acc-passwordemail');
Route::get('/password/reset/{token}', 'Account\Auth\ResetPasswordController@showResetForm')->name('acc-passwordreset');
Route::post('/password/reset', 'Account\Auth\ResetPasswordController@reset')->name('acc-passwordupdate');

// Email Verification Routes...
Route::get('/verify/{token}', 'Account\Auth\VerifyController@verify')->name('acc-verify');

// Steam Auth...
Route::match(['get', 'post'], '/steam_login', 'Account\Auth\SteamAuthController@steamLogin')->name('acc-steamlogin');
Route::match(['get', 'post'], '/steam_logout', 'Account\Auth\SteamAuthController@steamLogout')->name('acc-steamlogout');
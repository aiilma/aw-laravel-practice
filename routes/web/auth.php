<?php


/**
 * AUTH
 */
// Authentication Routes...
Route::get('/login', 'User\Auth\LoginController@showLoginForm')->name('acc-login');
Route::post('/login', 'User\Auth\LoginController@login');
Route::get('/logout', 'User\Auth\LoginController@userLogout')->name('acc-logout');
Route::post('/logout', 'User\Auth\LoginController@userLogout');

// Registration Routes...
if ($options['register'] ?? true) {
    Route::get('/register', 'User\Auth\RegisterController@showRegistrationForm')->name('acc-register');
    Route::post('/register', 'User\Auth\RegisterController@register');
}

// Password Reset Routes...
Route::get('/password/reset', 'User\Auth\ForgotPasswordController@showLinkRequestForm')->name('acc-passwordrequest');
Route::post('/password/email', 'User\Auth\ForgotPasswordController@sendResetLinkEmail')->name('acc-passwordemail');
Route::get('/password/reset/{token}', 'User\Auth\ResetPasswordController@showResetForm')->name('acc-passwordreset');
Route::post('/password/reset', 'User\Auth\ResetPasswordController@reset')->name('acc-passwordupdate');

// Email Verification Routes...
Route::get('/verify/{token}', 'User\Auth\VerifyController@verify')->name('acc-verify');

// Steam Auth...
Route::get('/steam_bind', 'User\Auth\SteamAuthController@handle')->name('acc-steam-bind');
// Route::post('/steam_logout', 'User\Auth\SteamAuthController@steamLogout')->name('acc-steamlogout');
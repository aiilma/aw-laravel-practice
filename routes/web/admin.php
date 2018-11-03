<?php

/**
 * ADMIN
 */
Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin-login');
Route::post('/login', 'Auth\AdminLoginController@login')->name('admin-loginsubmit');
Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin-logout');
Route::get('/', 'AdminController@index')->name('admin-dashboard');
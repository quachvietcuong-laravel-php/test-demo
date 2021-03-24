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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->name('admin.')->group(function() {
	// Admin Login
	Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
	Route::post('/login', 'Admin\Auth\LoginController@login')->name('login.submit');

	// Admin Pages
	Route::group(['middleware' => 'auth:admin'], function() {
		// Dashboard
		Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

		// Admin Accounts 
		Route::post('/auths/delete', 'Admin\Auth\AdminController@delete')->name('auth.delete');
		Route::resource('/auths', 'Admin\Auth\AdminController', ['names' => 'auth']);
	});
});
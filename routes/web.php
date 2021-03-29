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

Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function() {

	// Admin Login
	Route::namespace('Auth')->group(function() {
		Route::get('/login', 'LoginController@showLoginForm')->name('login');
		Route::post('/login', 'LoginController@login')->name('login.submit');
	});

	// Admin Pages
	Route::group(['middleware' => 'auth:admin'], function() {
		
		// Dashboard
		Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

		// Admin Accounts 
		Route::namespace('Auth')->group(function() {
			Route::post('/auths/delete', 'AdminController@delete')->name('auth.delete');
			Route::resource('/auths', 'AdminController', ['names' => 'auth']);
		});

		// Role Permission
		Route::prefix('role_permission')->namespace('RolePermission')->name('role_permission.')->group(function() {
			// Role
			Route::post('/roles/delete', 'RoleController@delete')->name('role.delete');
			Route::resource('/roles', 'RoleController', ['names' => 'role'])
				->only(['index', 'create', 'store', 'destroy']);

			// Permission
			Route::post('/permissions/delete', 'PermissionController@delete')->name('permission.delete');
			Route::resource('/permissions', 'PermissionController', ['names' => 'permission'])
				->only(['index', 'create', 'store', 'destroy']);
		});
	});
});
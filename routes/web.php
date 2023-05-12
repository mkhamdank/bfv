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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// route group prefix admin middleware auth from laravel ui
Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('/', 'DashboardController@index')->name('admin.dashboard');


    // route group prefix admin/vfi
    Route::group(['prefix' => 'vfi'], function () {

        Route::get('/', 'VfiController@index')->name('admin.vfi.index');


    });

    // route group prefix admin/role
    Route::group(['prefix' => 'role'], function () {

        Route::get('/', 'RoleController@index')->name('admin.role.index');
        Route::get('/create', 'RoleController@create')->name('admin.role.create');
        Route::post('/store', 'RoleController@store')->name('admin.role.store');
        Route::get('/show/{id}', 'RoleController@show')->name('admin.role.show');
        Route::get('/edit/{id}', 'RoleController@edit')->name('admin.role.edit');
        Route::put('/update/{id}', 'RoleController@update')->name('admin.role.update');
        Route::delete('/destroy/{id}', 'RoleController@destroy')->name('admin.role.destroy');

    });

    // route group prefix admin/permission
    Route::group(['prefix' => 'permission'], function () {

        Route::get('/', 'PermissionController@index')->name('admin.permission.index');
        Route::get('/create', 'PermissionController@create')->name('admin.permission.create');
        Route::post('/store', 'PermissionController@store')->name('admin.permission.store');
        Route::get('/show/{id}', 'PermissionController@show')->name('admin.permission.show');
        Route::get('/edit/{id}', 'PermissionController@edit')->name('admin.permission.edit');
        Route::put('/update/{id}', 'PermissionController@update')->name('admin.permission.update');
        Route::delete('/destroy/{id}', 'PermissionController@destroy')->name('admin.permission.destroy');

    });

    // route group prefix admin/permission
    Route::group(['prefix' => 'user'], function () {

        Route::get('/', 'UserController@index')->name('admin.user.index');
        Route::get('/create', 'UserController@create')->name('admin.user.create');
        Route::post('/store', 'UserController@store')->name('admin.user.store');
        Route::get('/show/{id}', 'UserController@show')->name('admin.user.show');
        Route::get('/edit/{id}', 'UserController@edit')->name('admin.user.edit');
        Route::put('/update/{id}', 'UserController@update')->name('admin.user.update');
        Route::delete('/destroy/{id}', 'UserController@destroy')->name('admin.user.destroy');

    });






});


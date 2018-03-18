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

// Home Page Routes
Route::get('/', 'Admin\HomeController@home')->name('home');
Route::get('/aboutus-text', 'Admin\HomeController@aboutUsText')->name('aboutus-text');
Route::post('/about-us', 'Admin\HomeController@updateAboutUs')->name('update.about-us');


//========= Admin Routes ==========
// Authentication routes...
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');

// Notice routes...
Route::resource('notices', 'Admin\NoticeController', ['except' => [
    'index'
]]);

// Our work routes...
Route::resource('works', 'Admin\WorkController', ['except' => [
    'index',
    'update',
    'destroy'
]]);
Route::post('works/{works}', 'Admin\WorkController@update')->name('works.update');
Route::get('works/delete/{work}', 'Admin\WorkController@destroy')->name('works.delete');

// Notice routes
Route::resource('notices', 'Admin\NoticeController', ['except' => [
    'index',
    'update',
    'destroy'
]]);
Route::post('notices/{notice}', 'Admin\NoticeController@update')->name('notices.update');
Route::get('notices/delete/{notice}', 'Admin\NoticeController@destroy')->name('notices.destroy');


// News routes...
Route::resource('news', 'Admin\NewsController', ['except' => [
    'update',
    'destroy'
]]);
Route::post('news/update/{news}', 'Admin\NewsController@update')->name('news.update');
Route::get('news/delete/{news}', 'Admin\NewsController@destroy')->name('news.destroy');


// Contact Us Routes...
Route::post('contact-us/update/{contactus}', 'Admin\ContactUsController@update')->name('contactus.update');


// Achievements routes...
Route::resource('achievemetns', 'Admin\AchievementsController', ['except' => [
    'update',
    'destroy'
]]);
Route::post('achievements/update/{achievement}', 'Admin\AchievementsController@update')->name('achievements.update');
Route::get('achievements/delete/{achievement}', 'Admin\AchievementsController@destroy')->name('achievements.destroy');

Auth::routes();

Route::get('/home', 'HomeController@index');

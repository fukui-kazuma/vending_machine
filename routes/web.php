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

Route::get('/', 'HomeController@index')->name('root.login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// 商品一覧画面
Route::get('/product/display','ProductController@showDisplay')->name('product.display');
// 商品登録画面
Route::get('/product/create','productController@showCreate')->name('product.create');
// 新規商品登録フォーム
Route::post('/product/store','productController@exeStore')->name('product.store');
// 商品詳細表示
Route::get('/product/{id}','ProductController@showDetail')->name('product.detail');
// 商品詳細編集画面
Route::get('/product/edit/{id}','ProductController@showedit')->name('product.edit');
Route::post('/product/update','ProductController@exeUpdate')->name('product.update');
Route::post('/produst/delete/{id}','ProductController@exeDelete')->name('product.delete');
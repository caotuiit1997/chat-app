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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('chat/{receiver_id}', 'MessagesController@index');
Route::post('sendmessage', 'MessagesController@sendMessage');
Route::get('writemessage', 'MessagesController@writemessage');
Route::get('users', 'UsersController@index');
Route::post('createConversation', 'ConversationController@createConversation');
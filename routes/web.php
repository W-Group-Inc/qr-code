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
Route::auth();
Route::get('qrLogin', ['uses' => 'QrLoginController@index']);
Route::get('qr-code/{id}', ['uses' => 'QrLoginController@indexoption2']);
Route::post('qrLogin', ['uses' => 'QrLoginController@checkUser']);
Route::get('checkUserTest/{id}', ['uses' => 'QrLoginController@checkUserTest']);
Route::get('breaks','QrLoginController@getBreaks');

Route::get('register-employee','UserController@registerEmployee');
Route::post('generate-qr-code','UserController@register');
Route::get('my-qrcode/{id}','UserController@ViewUserQrCode');


Route::get('employees','UserController@employees');
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
$userType='';
if(Auth::check())
{
	$userType=Auth::user()->userType->name;
}

Route::get('/','BaseController@getIndex');
Route::group(array('before'=>'guest'),function(){
	Route::get('account/login','AccountController@getLogin');
	Route::post('account/login','AccountController@postLogin');
});

Route::get('/admin','App\Controller\Admin\IndexController@index');
Route::get('/crm','App\Controller\Crm\IndexController@index');

Route::group(array('before'=>'admin','prefix'=>'admin'),function(){

});
Route::group(array('before'=>'crm','prefix'=>'crm'),function(){

});




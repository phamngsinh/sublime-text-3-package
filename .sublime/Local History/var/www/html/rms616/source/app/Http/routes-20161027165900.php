<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();


Route::post('password/change-password', [
    'as' => 'postChangePassword', 'uses' => 'UserController@postChange'
]);
Route::get('users/change-password', [
    'as' => 'changePassword', 'uses' => 'UserController@change'
]);


Route::resource('risks', 'RiskController');


// Add this route for checkout or submit form to pass the item into paypal
Route::get('payment/done', array(
    'as' => 'payment.done',
    'uses' => 'SubscriptionController@getDone',
));
Route::get('payment/cancel', array(
    'as' => 'payment.cancel',
    'uses' => 'SubscriptionController@getCancel',
));
Route::resource('subscriptions', 'SubscriptionController');

Route::get('subscriptions/upgrade-calculate/{id}', [
    'as' => 'subscriptions.upgradeCalculate', 'uses' => 'SubscriptionController@calculateUpgrade'
]);

Route::get('subscriptions/upgrade/{id}', [
    'as' => 'subscriptions.upgrade', 'uses' => 'SubscriptionController@upgrade'
]);


Route::resource('clients', 'ClientController');

Route::resource('users', 'UserController');
Route::get('/', 'UserController@index');


Route::group(['prefix' => 'ajax'], function () {
    Route::post('auth/validate-password', 'AjaxAuthController@postValidate');
    Route::resource('auth', 'AjaxAuthController');
});

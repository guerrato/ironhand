<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('member')->middleware(['cors'])->group(function () {
    Route::get('/', 'MemberController@getAll');
    Route::get('/{id}', 'MemberController@getMember');
    Route::post('/', 'MemberController@create');
    Route::put('/{id}', 'MemberController@update');
});

Route::prefix('ministry')->middleware(['cors'])->group(function () {
    Route::get('/', 'MinistryController@getAll');
    Route::get('/{id}', 'MinistryController@getMinistry');
    Route::post('/', 'MinistryController@create');
    Route::put('/{id}', 'MinistryController@update');

    Route::prefix('{ministry_id}/group')->group(function () {
        Route::get('/', 'GroupController@getAll');
        Route::get('/{id}', 'GroupController@getGroup');
        Route::post('/', 'GroupController@create');
        Route::put('/{id}', 'GroupController@update');
        Route::delete('/{id}', 'GroupController@delete');
        Route::put('/{id}/arrangemembers', 'GroupController@arrageMembers');
        Route::get('/{id}/getmembers', 'GroupController@getMembers');
    });
});

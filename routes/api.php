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

Route::prefix('ministry')->middleware('cors')->group(function () {
    Route::get('/', 'MinistryController@getAll');
    Route::get('/{id}', 'MinistryController@getMinistry')->where('id', '[0-9]+');
    Route::post('/', 'MinistryController@create');
    Route::put('/{id}', 'MinistryController@update')->where('id', '[0-9]+');
    Route::delete('/{id}', 'MinistryController@delete')->where('id', '[0-9]+');

    Route::prefix('{ministry_id}/member')->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('{role_id}/listrolesbyhierarchy', 'MemberRoleController@listRolesByHierarchy')->where('role_id', '[0-9]+');
        });

        Route::prefix('status')->group(function () {
            Route::get('/', 'MemberStatusController@getAll');
        });

        Route::get('/', 'MemberController@getAll');
        Route::get('/{id}', 'MemberController@getById')->where('id', '[0-9]+');
        Route::post('/', 'MemberController@create');
        Route::put('/{id}', 'MemberController@update')->where('id', '[0-9]+');
        Route::delete('/{id}', 'MemberController@delete')->where('id', '[0-9]+');
        Route::get('coordinators', 'MemberController@getCoordinators');
        Route::get('notallocatedcoordinators', 'MemberController@getNotAllocatedCoordinators');
        Route::get('notallocatedmembers', 'MemberController@getNotAllocatedMembers');
    });

    Route::prefix('{ministry_id}/group')->group(function () {
        Route::get('/', 'GroupController@getAll');
        Route::get('/{id}', 'GroupController@getGroup')->where('id', '[0-9]+');
        Route::post('/', 'GroupController@create');
        Route::put('/{id}', 'GroupController@update')->where('id', '[0-9]+');
        Route::delete('/{id}', 'GroupController@delete')->where('id', '[0-9]+');
        Route::put('/{id}/arrangemembers', 'GroupController@arrangeMember')->where('id', '[0-9]+');
        Route::get('/groupsofministry', 'GroupController@getGroupsOfMinistry');
        Route::get('/{id}/getmembers', 'GroupController@getMembers')->where('id', '[0-9]+');
    });
});

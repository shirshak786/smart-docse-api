<?php

Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'] , 'prefix' => 'user', 'namespace' => 'Modules\User\Http\Controllers\Admin'], function()
{
    Route::group(
        ['middleware' => ['can:view users']],
        function () {
            Route::get('users/active_counter', 'UserController@getActiveUserCounter')->name('users.active.counter');

            Route::get('users/roles', 'UserController@getRoles')->name('users.get_roles');

            Route::get('users/search', 'UserController@search')->name('users.search');
            Route::get('users/{user}/show', 'UserController@show')->name('users.show');

            Route::resource('users', 'UserController', [
                'only' => ['store', 'update', 'destroy'],
            ]);

            Route::post('users/batch_action', 'UserController@batchAction')->name('users.batch_action');
            Route::post('users/{user}/active', 'UserController@activeToggle')->name('users.active');

            Route::get('users/{user}/impersonate', 'UserController@impersonate')->name('users.impersonate');
        }
    );

    Route::group(
        ['middleware' => ['can:view roles']],
        function () {
            Route::get('roles/permissions', 'RoleController@getPermissions')->name('roles.get_permissions');

            Route::get('roles/search', 'RoleController@search')->name('roles.search');
            Route::get('roles/{role}/show', 'RoleController@show')->name('roles.show');

            Route::resource('roles', 'RoleController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
});





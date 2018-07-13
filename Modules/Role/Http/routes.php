<?php

Route::group(['middleware' => 'web', 'prefix' => 'role', 'namespace' => 'Modules\Role\Http\Controllers','as'=>'admin.'], function()
{
    Route::get('/', 'RoleController@index');
});

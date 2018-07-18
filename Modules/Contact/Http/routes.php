<?php

Route::group(['middleware' => ['web', 'auth', 'can:access backend'], 'prefix' => 'api/v1/admin/contacts', 'namespace' => 'Modules\Contact\Http\Controllers\Admin', 'as'=>'admin.contact.'], function () {
   Route::name('search')->get('search', 'ContactController@search');
   Route::name('show')->get('{contact}', 'ContactController@show');
});

Route::group(['middleware' => 'api', 'as'=>'contact.', 'prefix' => 'api/v1/contact', 'namespace' => 'Modules\Contact\Http\Controllers\User'], function()
{
    Route::name('store')->post('/', 'ContactController@store');
});

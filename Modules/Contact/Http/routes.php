<?php

Route::group(['middleware' => ['web', 'auth', 'can:access backend'], 'prefix' => 'api/v1/admin/contacts', 'namespace' => 'Modules\Contact\Http\Controllers\Admin', 'as'=>'admin.contacts.'], function () {
    Route::name('search')->get('search', 'ContactController@search');
});

Route::group(['middleware' => 'api', 'prefix' => 'contact', 'namespace' => 'Modules\Contact\Http\Controllers\User'], function () {
    Route::post('/', 'ContactController@store');
});

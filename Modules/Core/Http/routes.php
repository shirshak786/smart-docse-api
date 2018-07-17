<?php

Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'], 'prefix' => 'core', 'namespace' => 'Modules\Core\Http\Controllers\Admin', 'as'=>'admin.'], function () {
    Route::get('index/search', 'AjaxController@search')->name('search');
    Route::get('routes/search', 'AjaxController@routesSearch')->name('routes.search');
    Route::get('tags/search', 'AjaxController@tagsSearch')->name('tags.search');
    Route::post('images/upload', 'AjaxController@imageUpload')->name('images.upload');
});

Route::namespace('Modules\Core\Http\Controllers\User')->group(function () {
    Route::middleware(['web', 'locale', 'localize'])->group(function () {
        Route::get('/', 'UserController@index')->name('home');
    });
});

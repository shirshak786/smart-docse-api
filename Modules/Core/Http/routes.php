<?php

Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'], 'prefix' => 'core', 'namespace' => 'Modules\Core\Http\Controllers\Admin'], function()
{
    Route::get('index/search', 'AjaxController@search')->name('search');
    Route::get('routes/search', 'AjaxController@routesSearch')->name('routes.search');
    Route::get('tags/search', 'AjaxController@tagsSearch')->name('tags.search');
    Route::post('images/upload', 'AjaxController@imageUpload')->name('images.upload');

});

Route::namespace('Modules\Core\Http\Controllers\User')->group(function(){
    Route::middleware(['web', 'metas', 'locale', 'localize'])->group(function(){
        Route::get('/', 'UserController@index')->name('home');
    });
});


Route::namespace('Modules\Core\Http\Controllers\Admin')->group(function (){
    Route::get('/{vue_capture?}', 'AdminController@index')
        ->where('vue_capture', '[\/\w\.-]*')
        ->name('home');
});

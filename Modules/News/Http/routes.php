<?php

Route::group(['middleware' => ['web','auth','can:access backend'], 'prefix' => 'api/v1/admin/news', 'namespace' => 'Modules\News\Http\Controllers\Admin','as'=>'admin.news.'], function() {

    Route::name('index')->get('/','NewsController@index');
    Route::name('search')->get('search','NewsController@search');
    Route::name('store')->post('/','NewsController@store');
    Route::name('show')->get('{news}','NewsController@show');
    Route::name('update')->patch('{news}','NewsController@update');
    Route::name('delete')->delete('{news}','NewsController@delete');
});

Route::group(['middleware' => ['web'], 'prefix' => 'api/v1/news', 'namespace' => 'Modules\News\Http\Controllers\User','as'=>'news.'], function(){
    Route::name('index')->get('/','NewsController@index');
});

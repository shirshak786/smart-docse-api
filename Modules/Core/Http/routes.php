<?php

Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'], 'prefix' => 'core', 'namespace' => 'Modules\Core\Http\Controllers\Admin'], function()
{
    Route::get('index/search', 'AjaxController@search')->name('search');
    Route::get('routes/search', 'AjaxController@routesSearch')->name('routes.search');
    Route::get('tags/search', 'AjaxController@tagsSearch')->name('tags.search');
    Route::post('images/upload', 'AjaxController@imageUpload')->name('images.upload');

});

Route::middleware(['web', 'metas', 'locale', 'localize'])->group(function(){
    Route::get('/', 'FrontendController@index')->name('home');
    Route::get('robots.txt', 'SeoController@robots');
    Route::get('sitemap.xml', 'SeoController@sitemap');
});

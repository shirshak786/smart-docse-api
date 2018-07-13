<?php

Route::group(['middleware' => 'web', 'prefix' => 'seo', 'namespace' => 'Modules\SEO\Http\Controllers'], function()
{
    Route::get('/', 'SEOController@index');
});


Route::namespace('Modules\SEO\Http\Controllers\Admin')->group(function(){
    Route::middleware(['web', 'metas', 'locale', 'localize'])->group(function(){
        Route::get('robots.txt', 'SeoController@robots');
        Route::get('sitemap.xml', 'SeoController@sitemap');
    });
});

<?php

Route::group(['middleware' => 'web', 'prefix' => 'seo', 'namespace' => 'Modules\SEO\Http\Controllers', 'as'=>'admin.'], function () {
    Route::get('/', 'SEOController@index');
});

Route::namespace('Modules\SEO\Http\Controllers\Admin')->as('admin.')->group(function () {
    Route::middleware(['web', 'locale', 'localize'])->group(function () {
        Route::get('robots.txt', 'SeoController@robots');
        Route::get('sitemap.xml', 'SeoController@sitemap');
    });
});

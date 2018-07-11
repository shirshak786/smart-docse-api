<?php

Route::group(['middleware' => 'web', 'prefix' => 'seo', 'namespace' => 'Modules\SEO\Http\Controllers'], function()
{
    Route::get('/', 'SEOController@index');
});

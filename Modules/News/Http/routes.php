<?php

Route::group(['middleware' => 'web', 'prefix' => 'api/v1', 'namespace' => 'Modules\News\Http\Controllers'], function()
{
    Route::apiResource('news', 'NewsController');
});

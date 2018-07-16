<?php

Route::group(['middleware' => ['web','auth','can:access backend'], 'prefix' => 'api/v1', 'namespace' => 'Modules\News\Http\Controllers\Admin','as'=>'admin.'], function()
{
    Route::apiResource('news', 'NewsController');
});

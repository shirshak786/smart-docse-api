<?php

Route::group(['middleware' => 'web', 'prefix' => 'post', 'namespace' => 'Modules\Post\Http\Controllers'], function()
{
    Route::get('/', 'PostController@index');
});

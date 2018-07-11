<?php

Route::group(['middleware' => 'web', 'prefix' => 'redirection', 'namespace' => 'Modules\Redirection\Http\Controllers'], function()
{
    Route::get('/', 'RedirectionController@index');
});

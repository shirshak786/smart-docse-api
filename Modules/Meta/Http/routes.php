<?php

Route::group(['middleware' => 'web', 'prefix' => 'meta', 'namespace' => 'Modules\Meta\Http\Controllers'], function()
{
    Route::get('/', 'MetaController@index');
});

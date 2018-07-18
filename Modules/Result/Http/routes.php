<?php

Route::group(['middleware' => 'web', 'prefix' => 'result', 'namespace' => 'Modules\Result\Http\Controllers'], function () {
    Route::get('/', 'ResultController@index');
});

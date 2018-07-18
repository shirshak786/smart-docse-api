<?php

Route::group(['middleware' => 'web', 'prefix' => 'api/v1/admin/results/semester', 'namespace' => 'Modules\Result\Http\Controllers\Admin','as'=>'admin.result.semester'], function()
{
    Route::get('search', 'SemesterResultController@search');
    Route::post('/', 'SemesterResultController@store');
    Route::patch('/', 'SemesterResultController@update');
    Route::delete('/','SemesterResultController@destroy');
});

Route::group(['middleware'=>'api','prefix'=>'api/v1/results/semester', 'namespace' => 'Modules\Result\Http\Controllers\User','as'=>'result.semester'],function () {

});

<?php

Route::group(['middleware' => 'web', 'prefix' => 'api/v1/admin/results/semester', 'namespace' => 'Modules\Result\Http\Controllers\Admin', 'as'=>'admin.result.semester.'], function () {
    Route::name('search')->get('search', 'SemesterResultController@search');
    Route::name('show')->get('{result}/show', 'SemesterResultController@show');
    Route::name('store')->post('/', 'SemesterResultController@store');
    Route::name('update')->patch('{result}', 'SemesterResultController@update');
    Route::name('destroy')->delete('{result}', 'SemesterResultController@destroy');
    Route::name('batch_actions')->delete('/', 'SemesterResultController@batchActions');
});

Route::group(['middleware'=>'api', 'prefix'=>'api/v1/results/semester', 'namespace' => 'Modules\Result\Http\Controllers\User', 'as'=>'result.semester'], function () {
    Route::name('index')->get('/', 'SemesterResultController@index');
});

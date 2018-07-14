<?php

Route::group(['middleware' => 'web', 'prefix' => 'redirection','as'=>'admin.' ,'namespace' => 'Modules\Redirection\Http\Controllers\Admin'], function()
{
    Route::get('/', 'RedirectionController@index');
});



Route::group(
    ['middleware' => ['web','auth','can:access backend','can:view redirections'],'namespace'=> 'Modules\Redirection\Http\Controllers\Admin','as'=>'admin.',],
    function () {
        Route::get('redirections/redirection_types', 'RedirectionController@getRedirectionTypes')->name('redirections.get_redirection_types');

        Route::get('redirections/search', 'RedirectionController@search')->name('redirections.search');
        Route::get('redirections/{redirection}/show', 'RedirectionController@show')->name('redirections.show');

        Route::resource('redirections', 'RedirectionController', [
            'only' => ['store', 'update', 'destroy'],
        ]);

        Route::post('redirections/batch_action', 'RedirectionController@batchAction')->name('redirections.batch_action');
        Route::post('redirections/{redirection}/active', 'RedirectionController@activeToggle')->name('redirections.active');

        Route::post('redirections/import', 'RedirectionController@import')->name('redirections.import');
    }
);

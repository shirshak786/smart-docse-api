<?php

Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'], 'prefix' => 'meta', 'as' =>'admin.', 'namespace' => 'Modules\Meta\Http\Controllers\Admin'], function () {
    Route::group(
        ['middleware' => ['can:view metas']],
        function () {
            Route::get('metas/search', 'MetaController@search')->name('metas.search');
            Route::get('metas/{meta}/show', 'MetaController@show')->name('metas.show');

            Route::resource('metas', 'MetaController', [
                'only' => ['store', 'update', 'destroy'],
            ]);

            Route::post('metas/batch_action', 'MetaController@batchAction')->name('metas.batch_action');
        }
    );
});

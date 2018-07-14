<?php

Route::group(['middleware' => 'web', 'prefix' => 'blog', 'namespace' => 'Modules\Blog\Http\Controllers\User'], function()
{
    Route::get('/', 'BlogController@index');
});

Route::as('admin.')->namespace('Modules\Blog\Http\Controllers\Admin')->middleware('web')->group(function() {
    if (config('blog.enabled')) {
        Route::group(
            ['middleware' => ['can:view own posts']],
            function () {
                Route::get('posts/draft_counter', 'PostController@getDraftPostCounter')->name('posts.draft.counter');
                Route::get('posts/pending_counter', 'PostController@getPendingPostCounter')->name('posts.pending.counter');
                Route::get('posts/published_counter', 'PostController@getPublishedPostCounter')->name('posts.published.counter');
                Route::get('posts/latest', 'PostController@getLastestPosts')->name('posts.latest');

                Route::get('posts/search', 'PostController@search')->name('posts.search');
                Route::get('posts/{post}/show', 'PostController@show')->name('posts.show');

                Route::resource('posts', 'PostController', [
                    'only' => ['store', 'update', 'destroy'],
                ]);

                Route::post('posts/batch_action', 'PostController@batchAction')->name('posts.batch_action');
                Route::post('posts/{post}/pinned', 'PostController@pinToggle')->name('posts.pinned');
                Route::post('posts/{post}/promoted', 'PostController@promoteToggle')->name('posts.promoted');
            }
        );
    }
});


Route::namespace('Modules\Blog\Http\Controllers\User')->middleware('web')->group(function() {
    if (config('blog.enabled')) {
        Route::get('blog', 'BlogController@index')->name('blog.index');
        Route::get('blog/{post}', 'BlogController@show')->name('blog.show');
        Route::get('blog/tags/{tag}', 'BlogController@tag')->name('blog.tag');

        Route::get(
            LaravelLocalization::transRoute('routes.redactors'),
            'BlogController@owner'
        )->name('blog.owner');
    }
});

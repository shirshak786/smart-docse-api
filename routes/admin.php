<?php

Route::get('/{vue_capture?}', 'BackendController@index')
    ->where('vue_capture', '[\/\w\.-]*')
    ->name('home');

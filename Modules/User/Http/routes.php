<?php

// Admin Routes
Route::group(['middleware' => ['web', 'locale', 'auth', 'can:access backend'], 'prefix' => 'user', 'namespace' => 'Modules\User\Http\Controllers\Admin', 'as'=>'admin.'], function () {
    Route::group(
        ['middleware' => ['can:view users']],
        function () {
            Route::get('users/active_counter', 'UserController@getActiveUserCounter')->name('users.active.counter');

            Route::get('users/roles', 'UserController@getRoles')->name('users.get_roles');

            Route::get('users/search', 'UserController@search')->name('users.search');
            Route::get('users/{user}/show', 'UserController@show')->name('users.show');

            Route::resource('users', 'UserController', [
                'only' => ['store', 'update', 'destroy'],
            ]);

            Route::post('users/batch_action', 'UserController@batchAction')->name('users.batch_action');
            Route::post('users/{user}/active', 'UserController@activeToggle')->name('users.active');

            Route::get('users/{user}/impersonate', 'UserController@impersonate')->name('users.impersonate');
        }
    );

    Route::group(
        ['middleware' => ['can:view roles']],
        function () {
            Route::get('roles/permissions', 'RoleController@getPermissions')->name('roles.get_permissions');

            Route::get('roles/search', 'RoleController@search')->name('roles.search');
            Route::get('roles/{role}/show', 'RoleController@show')->name('roles.show');

            Route::resource('roles', 'RoleController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
});

Route::namespace('Modules\User\Http\Controllers\Auth')->group(function () {
    // Auth Routes
    Route::middleware(['web', 'locale', 'localize'])->group(function () {
        if (config('account.can_register')) {
            // Registration Routes...
            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register');
        }

        // Authentication Routes...
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('login/{provider}', 'LoginController@redirectToProvider')->name('social.login');
        Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback')->name('social.callback');

        // Password Reset Routes...
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset');

        // Admin specific login forms
        Route::get(config('app.admin_path').'/login', 'LoginController@showAdminLoginForm')->name('admin.login');
        Route::get(config('app.admin_path').'/logout', 'LoginController@adminLogout')->name('admin.logout');
        Route::get(config('app.admin_path').'/password/reset', 'ForgotPasswordController@showAdminLinkRequestForm')->name('admin.password.request');
    });
});

Route::namespace('Modules\User\Http\Controllers\User')->group(function () {
    // User Routes
    Route::middleware(['web', 'locale', 'localize'])->group(function () {
        Route::group(
            [
                'prefix'     => 'user',
                'as'         => 'user.',
                'middleware' => ['web', 'locale', 'auth'],
            ],
            function () {
                /*
                 * User Dashboard Specific
                 */
                Route::get('/', 'UserController@index')->name('home');

                /*
                 * User Account Specific
                 */
                Route::get('account', 'AccountController@index')->name('account');

                /*
                 * User Profile Update
                 */
                Route::patch('account/update', 'AccountController@update')->name('account.update');

                /*
                 * Password Change
                 */
                Route::patch('password/change', 'AccountController@changePassword')->name('password.change');

                /*
                 * Resend confirmation mail
                 */
                Route::get('confirmation/send', 'AccountController@sendConfirmation')->name('confirmation.send');

                /*
                 * Confirm email
                 */
                Route::get('email/confirm/{token}', 'AccountController@confirmEmail')->name('email.confirm');

                if (config('account.can_delete')) {
                    /*
                     * Account delete
                     */
                    Route::delete('account/delete', 'AccountController@delete')->name('account.delete');
                }
            }
        );
    });
});

Route::middleware(['web', 'locale', 'auth'])->group(function () {
    Route::name('admin.')->prefix(config('app.admin_path'))->namespace('Modules\Core\Http\Controllers\Admin')->group(function () {
        Route::get('/{vue_capture?}', 'AdminController@index')
            ->where('vue_capture', '[\/\w\.-]*')
            ->name('home');
    });
});

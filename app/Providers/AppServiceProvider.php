<?php

namespace App\Providers;

use App\Models\Post;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\URL;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentTagRepository;
use App\Repositories\EloquentMetaRepository;
use App\Repositories\EloquentPostRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\Contracts\TagRepository;
use App\Repositories\Contracts\MetaRepository;
use App\Repositories\Contracts\PostRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\EloquentAccountRepository;
use App\Repositories\Contracts\AccountRepository;
use App\Repositories\EloquentFormSettingRepository;
use App\Repositories\EloquentRedirectionRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Repositories\Contracts\FormSettingRepository;
use App\Repositories\Contracts\RedirectionRepository;
use App\Repositories\EloquentFormSubmissionRepository;
use App\Repositories\Contracts\FormSubmissionRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (config('app.url_force_https')) {
            // Force SSL if isSecure does not detect HTTPS
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Dusk, if env is appropriate
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

    }
}

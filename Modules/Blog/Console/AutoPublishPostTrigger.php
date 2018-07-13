<?php

namespace Modules\Blog\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Contracts\PostRepository;
use Modules\Blog\Models\Post;

class AutoPublishPostTrigger extends Command
{
    protected $signature = 'posts:publish';

    protected $description = 'Auto publish posts according to publish/unpublish dates';

    private $posts;

    public function __construct(PostRepository $posts)
    {
        parent::__construct();

        $this->posts = $posts;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();

        $this->posts->query()
            ->where('status', '!=', Post::PUBLISHED)
            ->where('published_at', '<', $now)
            ->where(function (Builder $q) use ($now) {
                $q
                    ->whereNull('unpublished_at')
                    ->orWhere('unpublished_at', '>', $now);
            })
            ->update(['status' => Post::PUBLISHED]);

        $this->posts->query()
            ->where('status', '=', Post::PUBLISHED)
            ->where('unpublished_at', '<', $now)
            ->update(['status' => Post::DRAFT]);
    }
}

<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Models\Tag;
use Modules\Blog\Models\Post;
use Modules\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;
use Modules\Blog\Contracts\PostRepository;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Repositories\EloquentBaseRepository;

class EloquentPostRepository extends EloquentBaseRepository implements PostRepository
{
    /**
     * EloquentUserRepository constructor.
     *
     * @param Post $post
     */
    public function __construct(
        Post $post
    ) {
        parent::__construct($post);
    }

    /**
     * @return mixed
     */
    public function published()
    {
        return $this->model
            ->published()
            ->with('owner')
            ->orderByDesc('pinned')
            ->orderByDesc('updated_at');
    }

    public function publishedByTag(Tag $tag)
    {
        return $this->published()->withAnyTags($tag->name);
    }

    public function publishedByOwner(User $user)
    {
        return $this->published()->withOwner($user);
    }

    public function findBySlug($slug)
    {
        return $this->query()->whereSlug($slug)->first();
    }

    public function saveAndPublish(Post $post, array $input, UploadedFile $image = null)
    {
        $post->status = Post::PUBLISHED;

        return $this->save($post, $input, $image);
    }

    public function saveAsDraft(Post $post, array $input, UploadedFile $image = null)
    {
        $post->status = Post::DRAFT;

        return $this->save($post, $input, $image);
    }

    private function save(Post $post, array $input, UploadedFile $image = null)
    {
        if ($post->exists) {
            if (! Gate::check('update', $post)) {
                throw new GeneralException(__('exceptions.backend.posts.save'));
            }
        } else {
            $post->user_id = auth()->id();
        }

        if (Post::PUBLISHED === $post->status && ! Gate::check('publish posts')) {
            // User with no publish permissions must go to moderation awaiting
            $post->status = Post::PENDING;
        }

        DB::transaction(function () use ($post, $input, $image) {
            if (! $post->save()) {
                throw new GeneralException(__('exceptions.backend.posts.save'));
            }

            if (isset($input['meta'])) {
                if (! $post->meta) {
                    $post->meta()->create($input['meta']);
                } else {
                    $post->meta->update($input['meta']);
                }
            } else {
                $post->meta()->create();
            }

            // Tags
            if (isset($input['tags'])) {
                $post->syncTags($input['tags']);
            }

            // Featured image
            /** @var Media $currentFeaturedImage */
            $currentFeaturedImage = $post->getMedia('featured image')->first();

            // Delete current image if replaced or delete asking
            if ($currentFeaturedImage && ($image || ! $input['has_featured_image'])) {
                $currentFeaturedImage->delete();
            }

            if ($image) {
                $post->addMedia($image)
                    ->toMediaCollection('featured image');
            }

            return true;
        });

        return true;
    }

    /**
     * @param Post $post
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function destroy(Post $post)
    {
        if (! $post->delete()) {
            throw new GeneralException(__('exceptions.backend.posts.delete'));
        }

        return true;
    }

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function batchQuery(array $ids)
    {
        $query = $this->query()->whereIn('id', $ids);

        return $query;
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDestroy(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (! Gate::check('delete posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            /** @var Post[] $posts */
            $posts = $query->get();

            foreach ($posts as $post) {
                $this->destroy($post);
            }

            return true;
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPublish(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (! Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if (Gate::check('publish posts')) {
                if ($query->update(['status' => Post::PUBLISHED])) {
                    return true;
                }
            } else {
                // Set to moderation pending if no right to publish
                if ($query->update(['status' => Post::PENDING])) {
                    return true;
                }
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPin(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (! Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if ($query->update(['pinned' => true])) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPromote(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (! Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if ($query->update(['promoted' => true])) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });

        return true;
    }
}

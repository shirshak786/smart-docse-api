<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Contracts\PostRepository;
use Modules\Blog\Http\Requests\StorePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Models\Post;
use Modules\Core\Http\Controllers\Admin\AdminController;
use Modules\Core\Utils\RequestSearchQuery;

class PostController extends AdminController
{
    protected $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function getDraftPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::DRAFT)->count();
    }

    public function getPendingPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::PENDING)->count();
    }

    public function getPublishedPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::PUBLISHED)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLastestPosts()
    {
        $query = $this->posts->query();

        if (! Gate::check('view posts')) {
            // Filter to only current user's posts
            $query->whereUserId(auth()->id());
        }

        return $query->orderByDesc('created_at')->limit(10)->get();
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        /** @var Builder $query */
        $query = $this->posts->query();

        if (! Gate::check('view posts')) {
            // Filter to only current user's posts
            $query->whereUserId(auth()->id());
        }

        $query
            ->join('users', 'users.id', '=', 'user_id');

        $requestSearchQuery = new RequestSearchQuery($request, $query, [
            'title',
            'summary',
            'body',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'title',
                'status',
                'pinned',
                'promoted',
                'posts.created_at',
                'posts.updated_at',
            ],
                [
                    __('validation.attributes.title'),
                    __('validation.attributes.status'),
                    __('validation.attributes.pinned'),
                    __('validation.attributes.promoted'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'posts');
        }

        return $requestSearchQuery->result([
            'posts.id',
            'user_id',
            'users.name as owner',
            'title',
            'posts.slug',
            'status',
            'pinned',
            'promoted',
            'posts.created_at',
            'posts.updated_at',
        ]);
    }


    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return $post;
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('create posts');

        /** @var Post $post */
        $post = $this->posts->make(
            $request->only('title', 'summary', 'body', 'published_at', 'unpublished_at', 'pinned', 'promoted')
        );

        if ('publish' === $request->input('status')) {
            $this->posts->saveAndPublish($post, $request->input(), $request->file('featured_image'));
        } else {
            $this->posts->saveAsDraft($post, $request->input(), $request->file('featured_image'));
        }

        return $this->redirectResponse($request, __('alerts.backend.posts.created'));
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $this->authorize('update', $post);

        $post->fill(
            $request->only('title', 'summary', 'body', 'published_at', 'unpublished_at', 'pinned', 'promoted')
        );

        if ('publish' === $request->input('status')) {
            $this->posts->saveAndPublish($post, $request->input(), $request->file('featured_image'));
        } else {
            $this->posts->saveAsDraft($post, $request->input(), $request->file('featured_image'));
        }

        return $this->redirectResponse($request, __('alerts.backend.posts.updated'));
    }


    public function destroy(Post $post, Request $request)
    {
        $this->authorize('delete', $post);

        $this->posts->destroy($post);

        return $this->redirectResponse($request, __('alerts.backend.posts.deleted'));
    }


    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                $this->authorize('delete posts');

                $this->posts->batchDestroy($ids);

                return $this->redirectResponse($request, __('alerts.backend.posts.bulk_destroyed'));
                break;
            case 'publish':
                $this->authorize('edit posts');

                $this->posts->batchPublish($ids);

                if (Gate::check('publish posts')) {
                    return $this->redirectResponse($request, __('alerts.backend.posts.bulk_published'));
                }

                return $this->redirectResponse($request, __('alerts.backend.posts.bulk_pending', 'warning'));
                break;
            case 'pin':
                $this->authorize('edit posts');

                $this->posts->batchPin($ids);

                return $this->redirectResponse($request, __('alerts.backend.posts.bulk_pinned'));
                break;
            case 'promote':
                $this->authorize('edit posts');

                $this->posts->batchPromote($ids);

                return $this->redirectResponse($request, __('alerts.backend.posts.bulk_promoted'));
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    public function pinToggle(Post $post)
    {
        $this->authorize('edit posts');
        $post->update(['pinned' => ! $post->pinned]);
    }

    public function promoteToggle(Post $post)
    {
        $this->authorize('edit posts');
        $post->update(['promoted' => ! $post->promoted]);
    }
}

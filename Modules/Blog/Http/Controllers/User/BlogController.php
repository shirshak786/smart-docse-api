<?php

namespace Modules\Blog\Http\Controllers\Frontend;

use Modules\Blog\Contracts\PostRepository;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Tag;
use Modules\Core\Http\Controllers\User\UserController;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Artesaos\SEOTools\Facades\SEOMeta;

class BlogController extends UserController
{
    protected $posts;

    public function __construct(PostRepository $posts)
    {
        parent::__construct();

        $this->posts = $posts;
    }

    public function index()
    {
        return view('frontend.blog.index')->withPosts(
            $this->posts->published()->paginate(9)
        );
    }

    public function tag(Tag $tag)
    {
        $this->setTranslatable($tag);

        return view('frontend.blog.tag')->withTag($tag)->withPosts(
            $this->posts->publishedByTag($tag)->paginate(9)
        );
    }

    public function owner(User $user)
    {
        $this->setLocalesAttributes(['user' => $user->slug]);

        return view('frontend.blog.owner')
            ->withUser($user)
            ->withPosts($this->posts->publishedByOwner($user)->paginate(9));
    }

    public function show(Post $post, Request $request)
    {
        // If not published, only user with edit access can see it
        if (! $post->published && ! Gate::check('update', $post)) {
            abort(404);
        }

        SEOMeta::setTitle($post->meta_title);
        SEOMeta::setDescription($post->meta_description);

        $this->setTranslatable($post);

        return view('frontend.blog.show')->withPost($post);
    }
}

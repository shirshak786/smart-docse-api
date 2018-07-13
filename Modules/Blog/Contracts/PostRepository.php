<?php

namespace Modules\Blog\Contracts;

use Modules\Blog\Models\Post;
use Modules\Blog\Models\Tag;
use Modules\User\Models\User;
use Illuminate\Http\UploadedFile;
use Modules\Core\Contracts\BaseRepository;

interface PostRepository extends BaseRepository
{
    public function published();

    public function publishedByTag(Tag $tag);

    public function publishedByOwner(User $user);

    public function findBySlug($slug);

    public function saveAndPublish(Post $post, array $input, UploadedFile $image = null);

    public function saveAsDraft(Post $post, array $input, UploadedFile $image = null);

    public function destroy(Post $post);

    public function batchDestroy(array $ids);

    public function batchPublish(array $ids);

    public function batchPromote(array $ids);

    public function batchPin(array $ids);
}

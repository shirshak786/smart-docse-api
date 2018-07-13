<?php

namespace Modules\Blog\Contracts;

use Modules\Core\Contracts\BaseRepository;

/**
 * Interface TagRepository.
 */
interface TagRepository extends BaseRepository
{
    /**
     * @param string $slug
     *
     * @return mixed
     */
    public function findBySlug($slug);
}

<?php

namespace Modules\Blog\Contracts;

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

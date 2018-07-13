<?php

namespace Modules\Meta\Models\Traits;

use Modules\Meta\Models\Meta;

/**
 * Trait Metable.
 */
trait Metable
{
    /**
     * Get the meta for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Meta
     */
    public function meta()
    {
        return $this->morphOne(Meta::class, 'metable');
    }
}

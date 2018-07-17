<?php

namespace Modules\Meta\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Models\Traits\TranslatableJson;

class Meta extends Model
{
    use HasTranslations;
    use TranslatableJson;

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'description',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'route',
        'title',
        'description',
    ];

    protected $appends = ['can_edit', 'can_delete'];

    public function getCanEditAttribute()
    {
        return true;
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete metas');
    }

    public function metable()
    {
        return $this->morphTo();
    }
}

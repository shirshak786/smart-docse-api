<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}

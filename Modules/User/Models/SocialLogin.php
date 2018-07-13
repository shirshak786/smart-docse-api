<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLogin extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];
}

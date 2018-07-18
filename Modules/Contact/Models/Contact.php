<?php

namespace Modules\Contact\Models\Contact;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'sender_name',
        'email',
        'subject',
        'content',
    ];
}

<?php

namespace Modules\Contact\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use function request;

class Contact extends Model
{
    protected $fillable = [
        'sender_name',
        'email',
        'subject',
        'content',
    ];

    public static function storeValidation() {
        return [
          'sender_name' => 'required',
          'email' => 'required|email',
          'subject' => 'required',
          'content' => 'required'
        ];
    }
}

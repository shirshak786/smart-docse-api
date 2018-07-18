<?php

namespace Modules\Contact\Http\Requests;

use Modules\Contact\Models\Contact\Contact;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return Contact::storeValidation();
    }
}

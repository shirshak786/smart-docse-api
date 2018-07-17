<?php

namespace Modules\News\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\News\Models\News;

class StoreNewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return News::storeValidation();
    }
}

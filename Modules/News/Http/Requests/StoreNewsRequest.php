<?php

namespace Modules\News\Http\Requests;

use Modules\News\Models\News;
use Illuminate\Foundation\Http\FormRequest;

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

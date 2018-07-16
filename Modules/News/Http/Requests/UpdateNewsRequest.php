<?php

namespace Modules\News\Http\Requests;

use Illuminate\Support\Facades\Request;
use Modules\News\Models\News;

class UpdateNewsRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return News::updateValidation();
    }
}

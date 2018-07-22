<?php

namespace Modules\Result\Http\Requests\Admin;

use Modules\Result\Models\SemesterResult;
use Illuminate\Foundation\Http\FormRequest;

class StoreSemesterResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return SemesterResult::storeValidation();
    }
}

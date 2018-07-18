<?php

namespace Modules\Result\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Result\Models\SemesterResult;

class UpdateSemesterResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return SemesterResult::updateValidation();
    }
}

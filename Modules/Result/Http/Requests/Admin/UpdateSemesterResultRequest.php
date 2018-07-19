<?php

namespace Modules\Result\Http\Requests\Admin;

use Modules\Result\Models\SemesterResult;
use Illuminate\Foundation\Http\FormRequest;

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

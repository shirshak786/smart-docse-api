<?php

namespace Modules\Result\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class SemesterResult extends Model
{
    protected $table = 'semester_results';

    protected $fillable = [
        'subject',
        'semester',
        'faculty',
        'result_data',
        'status',
    ];

    protected $casts = [
      'result_data' => 'array',
    ];

    public static function storeValidation() {
        return [
            'subject' => 'required',
            'semester' => 'required|integer|min:1|max:8',
            'faculty' => 'required',
            'result_data' => 'required|json',
            'status' => 'required'
        ];
    }

    public static function updateValidation() {
        return [
            'subject' => 'required',
            'semester' => 'required|integer|min:1|max:8',
            'faculty' => 'required',
            'result_data' => 'required|json',
            'status' => 'required'
        ];
    }

    public function getCanEditAttribute()
    {
        return Gate::allows('update', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::allows('delete', $this);
    }


}

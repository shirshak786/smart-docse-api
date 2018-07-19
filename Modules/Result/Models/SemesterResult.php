<?php

namespace Modules\Result\Models;

use Exception;
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

    protected $appends = [
        'can_edit',
        'can_delete',
      'status_label',
        'status_label_color',
    ];

    protected $casts = [
      'result_data' => 'array',
    ];

    public static function storeValidation() {
        return [
            'subject' => 'required',
            'semester' => 'required|integer|min:1|max:8',
            'faculty' => 'required',
            'result_data' => 'required',
            'result_data.*.name' => 'required',
            'result_data.*.marks' => 'required|integer',
            'result_data.*.roll' => 'required|integer',
            'status' => 'required'
        ];
    }

    public static function updateValidation() {
        return [
            'subject' => 'required',
            'semester' => 'required|integer|min:1|max:8',
            'faculty' => 'required',
            'result_data' => 'required',
            'result_data.*.marks' => 'required|integer',
            'result_data.*.roll' => 'required|integer',
            'result_data.*.name' => 'required',
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

    public function getStatusesAttribute()
    {
        return [
            0 => 'Pending',
            1 => 'Waiting',
            2 => 'Published',
        ];
    }

    public function getStatusesLabelColor()
    {
        return [
            0 => 'danger',
            1 => 'warning',
            2 => 'success'
        ];
    }


    public function getStatusLabelAttribute()
    {
        $status = $this->statuses;

        if (array_key_exists($this->status, $status)) {
            return $status[$this->status];
        }
        throw new Exception('The Status you supplied doesn\'t exist');
    }

    public function getStatusLabelColorAttribute()
    {
        $colors = $this->getStatusesLabelColor();

        if (array_key_exists($this->status, $colors)) {
            return $colors[$this->status];
        }

        throw new Exception('The Status you supplied doesn\'t exist');
    }
}

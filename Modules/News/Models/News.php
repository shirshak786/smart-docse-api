<?php

namespace Modules\News\Models;

use function array_key_exists;
use Carbon\Carbon;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class News extends Eloquent implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;


    protected $table = 'news';

    protected $fillable = [
        'title',
        'content',
        'type',
    ];

    protected $appends = [
        'cover_image',
        'cover_image_link',
        'attachment',
        'attachment_link',
        'status_label',
        'statuses',
        'status_value',
        'types',
        'types_value',
    ];

    public static function storeValidation()
    {
        return [
          'title' => 'required|min:3',
          'content'=>'required|min:3',
          'type'=>'required|integer',
          'status'=>'required|integer',
          'published_date' =>  'date_format:' . config('app.date_format') . '|max:191|nullable',
          'cover_image' => 'file|image|required'
        ];
    }

    public static function updateValidation()
    {
        return [
            'title' => 'required|min:3',
            'content'=>'required|min:3',
            'type'=>'required|integer',
            'status'=>'required|integer',
            'cover_image' => 'file|image|nullable'
        ];
    }

    public function getStatusesAttribute() {
        return [
            0 => 'PENDING',
            1 => 'WAITING',
            2 => 'APPROVED',
        ];
    }

    public function getStatusValueAttribute() {
        $status = $this->statuses();

        if( array_key_exists($this->status, $status)){
            return $status[$this->status];
        }
        throw new Exception('The Status you supplied doesn\'t exist');
    }

    public function getTypesAttribute() {
        return [
            0 => 'KU Related',
            1 => 'DOCSE Related',
            2 => 'DOCSE First Semester',
            3 =>  'DOCSE Second Semester',
            4 => 'DOCSE Third Semester',
            5 => 'DOCSE Fourth Semester',
            6 => 'DOCSE Fifth Semester',
            7 => 'DOCSE Sixth Semester',
            8 => 'DOCSE Seventh Semester',
            9 => 'DOCSE Eight Semester',
        ];
    }

    public function getTypesValueAttribute()
    {
        $type = $this->types();

        if( array_key_exists($this->type, $type)){
            return $type[$this->status];
        }
        throw new Exception('The Type you supplied doesn\'t exist');
    }


    public function setPublishedDateAttribute($input)
    {
        if ($input) {
            $this->attributes['published_date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        }
    }

    public function gePublishedDateAttribute($output)
    {
        if ($output) {
             return Carbon::createFromFormat('Y-m-d', $output)->format(config('app.date_format'));
        }
    }

    public function getCoverImageAttribute()
    {
        return $this->getFirstMedia('cover_image');
    }

    public function getCoverImageLinkAttribute()
    {
        $file = $this->cover_image;

        if (! $file) {
            return null;
        }

        return '<a href="' . $file->getUrl() . '" target="_blank">' . $file->file_name . '</a>';
    }

    public function author() {
        return $this->belongsTo(User::class,'author_id');
    }
}

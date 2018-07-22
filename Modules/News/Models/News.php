<?php

namespace Modules\News\Models;

use Exception;
use Carbon\Carbon;
use Laravel\Scout\Searchable;
use Modules\Core\Models\Traits\HasEditor;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;

class News extends Model implements HasMedia
{
    use Searchable;
    use SoftDeletes;
    use HasMediaTrait;
    use Sluggable;
    use HasEditor;

    protected $table = 'news';

    protected $with = ['author'];

    public $editorFields = [
        'body',
    ];

    public $editorCollectionName = 'editor images';

    public $asYouType = true;

    protected $fillable = [
        'title',
        'content',
        'type',
        'status',
        'published_date',
    ];

    protected $appends = [
        'can_edit',
        'can_delete',
        'cover_image',
        'cover_image_url',
        'cover_image_thumbnail_url',
        'statuses',
        'status_value',
        'status_label_color',
        'types',
        'type_value',
    ];

    public static function storeValidation()
    {
        return [
          'title'              => 'required|min:3',
          'content'            => 'required|min:3',
          'type'               => 'required|integer',
          'status'             => 'required|integer',
          'published_date'     => 'date_format:'.config('app.date_format').'|max:191|nullable',
          'cover_image_upload' => 'file|image|required',
        ];
    }

    public static function updateValidation()
    {
        return [
            'title'              => 'required|min:3',
            'content'            => 'required|min:3',
            'type'               => 'required|integer',
            'status'             => 'required|integer',
            'cover_image_upload' => 'nullable|file|image',
        ];
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

    public function getStatusValueAttribute()
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

    public function getTypesAttribute()
    {
        return [
            0  => 'KU Related',
            1  => 'DOCSE Related',
            2  => 'DOCSE First Semester',
            3  => 'DOCSE Second Semester',
            4  => 'DOCSE Third Semester',
            5  => 'DOCSE Fourth Semester',
            6  => 'DOCSE Fifth Semester',
            7  => 'DOCSE Sixth Semester',
            8  => 'DOCSE Seventh Semester',
            9  => 'DOCSE Eight Semester',
            10 => 'KUCC',
        ];
    }

    public function getTypeValueAttribute()
    {
        $types = $this->types;
        if( array_key_exists($this->type, $types)){
            return $types[$this->type];
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

    public function getCoverImageURLAttribute()
    {
        $file = $this->cover_image;

        if (! $file) {
            return '/images/placeholder.png';
        }

        return $file->getUrl();
    }

    public function getCoverImageThumbnailUrlAttribute()
    {
        return image_template_url('small', $this->cover_image_url);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getCanEditAttribute()
    {
        return Gate::allows('update', $this);
    }

    public function getCanDeleteAttribute()
    {
        return Gate::allows('delete', $this);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

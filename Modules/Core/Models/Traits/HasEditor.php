<?php

namespace Modules\Core\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia\HasMedia;

trait HasEditor
{
    public static function bootHasEditor()
    {
        static::saved(function (Model $model) {
            if ($model instanceof HasMedia && property_exists($model, 'editorFields')) {
                foreach ($model->editorFields as $field) {
                    $model->saveImagesToMediaCollection($field);
                }
            }
        });
    }

    protected function parseTextForImages(string $text)
    {
        $updated = false;
        preg_match_all('@src="([^"]+)"@', $text, $match);
        $src = collect(array_pop($match))->unique();

        foreach ($src as $path) {
            $startPath = '/storage/tmp/';
            if (starts_with($path, $startPath)) {
                $file = Storage::disk('public')->path(str_replace('/storage/', '', $path));

                $media = $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($this->editorCollectionName);

                $imagePath =  $media->getFullUrl();
                $text = str_replace($path, $imagePath, $text);
                $updated = true;
            }
        }
        return $updated ? $text : false;
    }

    protected function saveImagesToMediaCollection(string $field)
    {
        $data = $this->parseTextForImages($this->$field);
        if( $data){
            $this->$field = $data;
            $this->save();
        }
    }   
}

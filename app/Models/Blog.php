<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $appends = ['totalComment'];

    public function getTotalCommentAttribute()
    {
        return $this->activeComments()->count();
    }


    public function category(){
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class)->select('id','name','image','email');
    }

    public function comments(){
        return $this->hasMany(BlogComment::class);
    }

    public function activeComments(){
        return $this->hasMany(BlogComment::class)->where('status',1);
    }

    public function getTranslatedTitleAttribute()
    {
        if ($title = $this->translation?->title) {
            return $title;
        }
        return $this->title;
    }

    public function getTranslatedShortDescriptionAttribute()
    {
        if ($short_description = $this->translation?->short_description) {
            return $short_description;
        }
        return $this->short_description;
    }

    public function getTranslatedDescriptionAttribute()
    {
        if ($description = $this->translation?->description) {
            return $description;
        }
        return $this->description;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(BlogTranslation::class, 'blog_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(BlogTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = BlogTranslation::firstOrCreate([
                'language_code' => 'en',
                'blog_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->short_description = $model->short_description;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = BlogTranslation::firstOrCreate([
                'language_code' => 'en',
                'blog_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->short_description = $model->short_description;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }


}

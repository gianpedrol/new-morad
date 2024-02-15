<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    public function blogs(){
        return $this->hasMany(Blog::class);
    }

    public function getTranslatedNameAttribute()
    {
        if ($name = $this->translation?->name) {
            return $name;
        }
        return $this->name;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(BlogCategoryTranslation::class, 'blog_category_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = BlogCategoryTranslation::firstOrCreate([
                'language_code' => 'en',
                'blog_category_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = BlogCategoryTranslation::firstOrCreate([
                'language_code' => 'en',
                'blog_category_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

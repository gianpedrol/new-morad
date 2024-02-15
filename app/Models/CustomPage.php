<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use HasFactory;

    public function getTranslatedPageNameAttribute()
    {
        if ($page_name = $this->translation?->page_name) {
            return $page_name;
        }
        return $this->page_name;
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

        return $this->hasOne(CustomPageTranslation::class, 'custom_page_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(CustomPageTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = CustomPageTranslation::firstOrCreate([
                'language_code' => 'en',
                'custom_page_id' => $model->id,
            ]);

            $defaultTranslation->page_name = $model->page_name;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = CustomPageTranslation::firstOrCreate([
                'language_code' => 'en',
                'custom_page_id' => $model->id,
            ]);

            $defaultTranslation->page_name = $model->page_name;
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

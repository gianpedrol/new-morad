<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    public function getTranslatedTitleAttribute()
    {
        if ($title = $this->translation?->title) {
            return $title;
        }
        return $this->title;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(SliderTranslation::class, 'slider_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(SliderTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = SliderTranslation::firstOrCreate([
                'language_code' => 'en',
                'slider_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = SliderTranslation::firstOrCreate([
                'language_code' => 'en',
                'slider_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

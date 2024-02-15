<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function getTranslatedTitleAttribute()
    {
        if ($title = $this->translation?->title) {
            return $title;
        }
        return $this->title;
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

        return $this->hasOne(ServiceTranslation::class, 'service_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = ServiceTranslation::firstOrCreate([
                'language_code' => 'en',
                'service_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = ServiceTranslation::firstOrCreate([
                'language_code' => 'en',
                'service_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
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

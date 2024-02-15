<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearestLocation extends Model
{
    use HasFactory;

    public function getTranslatedLocationAttribute()
    {
        if ($location = $this->translation?->location) {
            return $location;
        }
        return $this->location;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(NearestLocationTranslation::class, 'nearest_location_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(NearestLocationTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = NearestLocationTranslation::firstOrCreate([
                'language_code' => 'en',
                'nearest_location_id' => $model->id,
            ]);

            $defaultTranslation->location = $model->location;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = NearestLocationTranslation::firstOrCreate([
                'language_code' => 'en',
                'nearest_location_id' => $model->id,
            ]);

            $defaultTranslation->location = $model->location;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

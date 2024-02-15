<?php

namespace App\Models;

use App\Models\AminityTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aminity extends Model
{
    use HasFactory;

    public function getTranslatedAminityAttribute()
    {
        if ($aminity = $this->translation?->aminity) {
            return $aminity;
        }
        return $this->aminity;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(AminityTranslation::class, 'aminity_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(AminityTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = AminityTranslation::firstOrCreate([
                'language_code' => 'en',
                'aminity_id' => $model->id,
            ]);

            $defaultTranslation->aminity = $model->aminity;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = AminityTranslation::firstOrCreate([
                'language_code' => 'en',
                'aminity_id' => $model->id,
            ]);

            $defaultTranslation->aminity = $model->aminity;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

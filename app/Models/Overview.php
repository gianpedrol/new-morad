<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overview extends Model
{
    use HasFactory;

    public function getTranslatedNameAttribute()
    {
        if ($this->translation?->name) {
            return $this->translation->name;
        }
        return $this->name;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(OverviewTranslation::class, 'overview_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(OverviewTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = OverviewTranslation::firstOrCreate([
                'language_code' => 'en',
                'overview_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = OverviewTranslation::firstOrCreate([
                'language_code' => 'en',
                'overview_id' => $model->id,
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    public function getTranslatedTypeAttribute()
    {
        if ($type = $this->translation?->type) {
            return $type;
        }
        return $this->type;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(PropertyTypeTranslation::class, 'property_type_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(PropertyTypeTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = PropertyTypeTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_type_id' => $model->id,
            ]);

            $defaultTranslation->type = $model->type;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = PropertyTypeTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_type_id' => $model->id,
            ]);

            $defaultTranslation->type = $model->type;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

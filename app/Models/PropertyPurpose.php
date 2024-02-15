<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPurpose extends Model
{
    use HasFactory;

    public function getTranslatedCustomPurposeAttribute()
    {
        if ($custom_purpose = $this->translation?->custom_purpose) {
            return $custom_purpose;
        }
        return $this->custom_purpose;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(PropertyPurposeTranslation::class, 'property_purpose_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(PropertyPurposeTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = PropertyPurposeTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_purpose_id' => $model->id,
            ]);

            $defaultTranslation->custom_purpose = $model->custom_purpose;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = PropertyPurposeTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_purpose_id' => $model->id,
            ]);

            $defaultTranslation->custom_purpose = $model->custom_purpose;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

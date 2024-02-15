<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    public function getTranslatedNameAttribute()
    {
        if ($name = $this->translation?->name) {
            return $name;
        }
        return $this->name;
    }

    public function getTranslatedDesignationAttribute()
    {
        if ($designation = $this->translation?->designation) {
            return $designation;
        }
        return $this->designation;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(PartnerTranslation::class, 'partner_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(PartnerTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = PartnerTranslation::firstOrCreate([
                'language_code' => 'en',
                'partner_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->designation = $model->designation;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = PartnerTranslation::firstOrCreate([
                'language_code' => 'en',
                'partner_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->designation = $model->designation;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

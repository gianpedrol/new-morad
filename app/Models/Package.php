<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public function getTranslatedPackageNameAttribute()
    {
        if ($package_name = $this->translation?->package_name) {
            return $package_name;
        }
        return $this->package_name;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(PackageTranslation::class, 'package_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(PackageTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = PackageTranslation::firstOrCreate([
                'language_code' => 'en',
                'package_id' => $model->id,
            ]);

            $defaultTranslation->package_name = $model->package_name;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = PackageTranslation::firstOrCreate([
                'language_code' => 'en',
                'package_id' => $model->id,
            ]);

            $defaultTranslation->package_name = $model->package_name;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

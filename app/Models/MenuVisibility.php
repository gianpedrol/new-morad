<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuVisibility extends Model
{
    use HasFactory;

    public function getTranslatedCustomNameAttribute()
    {
        if ($custom_name = $this->translation?->custom_name) {
            return $custom_name;
        }
        return $this->custom_name;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(MenuVisibilityTranslation::class, 'menu_visibility_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(MenuVisibilityTranslation::class);
    }


    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = MenuVisibilityTranslation::firstOrCreate([
                'language_code' => 'en',
                'menu_visibility_id' => $model->id,
            ]);

            $defaultTranslation->custom_name = $model->custom_name;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = MenuVisibilityTranslation::firstOrCreate([
                'language_code' => 'en',
                'menu_visibility_id' => $model->id,
            ]);

            $defaultTranslation->custom_name = $model->custom_name;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

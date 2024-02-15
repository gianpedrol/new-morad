<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    public function getTranslatedAddressAttribute()
    {
        if ($address = $this->translation?->address) {
            return $address;
        }
        return $this->address;
    }

    public function getTranslatedFirstColumnAttribute()
    {
        if ($first_column = $this->translation?->first_column) {
            return $first_column;
        }
        return $this->first_column;
    }

    public function getTranslatedSecondColumnAttribute()
    {
        if ($second_column = $this->translation?->second_column) {
            return $second_column;
        }
        return $this->second_column;
    }

    public function getTranslatedThirdColumnAttribute()
    {
        if ($third_column = $this->translation?->third_column) {
            return $third_column;
        }
        return $this->third_column;
    }

    public function getTranslatedCopyrightAttribute()
    {
        if ($copyright = $this->translation?->copyright) {
            return $copyright;
        }
        return $this->copyright;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(FooterTranslation::class, 'footer_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = FaqTranslation::firstOrCreate([
                'language_code' => 'en',
                'footer_id' => $model->id,
            ]);

            $defaultTranslation->address = $model->address;
            $defaultTranslation->first_column = $model->first_column;
            $defaultTranslation->second_column = $model->second_column;
            $defaultTranslation->third_column = $model->third_column;
            $defaultTranslation->copyright = $model->copyright;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = FaqTranslation::firstOrCreate([
                'language_code' => 'en',
                'footer_id' => $model->id,
            ]);

            $defaultTranslation->address = $model->address;
            $defaultTranslation->first_column = $model->first_column;
            $defaultTranslation->second_column = $model->second_column;
            $defaultTranslation->third_column = $model->third_column;
            $defaultTranslation->copyright = $model->copyright;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

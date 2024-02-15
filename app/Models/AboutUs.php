<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    public function getTranslatedAboutUsAttribute()
    {
        if ($about_us = $this->translation?->about_us) {
            return $about_us;
        }
        return $this->about_us;
    }
    public function getTranslatedServiceAttribute()
    {
        if ($service = $this->translation?->service) {
            return $service;
        }
        return $this->service;
    }
    public function getTranslatedTeamTitleAttribute()
    {
        if ($team_title = $this->translation?->team_title) {
            return $team_title;
        }
        return $this->team_title;
    }
    public function getTranslatedTeamDescriptionAttribute()
    {
        if ($team_description = $this->translation?->team_description) {
            return $team_description;
        }
        return $this->team_description;
    }
    public function getTranslatedHistoryAttribute()
    {
        if ($history = $this->translation?->history) {
            return $history;
        }
        return $this->history;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(AboutUsTranslation::class, 'about_us_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(AboutUsTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = AboutUsTranslation::firstOrCreate([
                'language_code' => 'en',
                'about_us_id' => $model->id,
            ]);

            $defaultTranslation->about_us = $model->about_us;
            $defaultTranslation->service = $model->service;
            $defaultTranslation->team_title = $model->team_title;
            $defaultTranslation->team_description = $model->team_description;
            $defaultTranslation->history = $model->history;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = AboutUsTranslation::firstOrCreate([
                'language_code' => 'en',
                'about_us_id' => $model->id,
            ]);

            $defaultTranslation->about_us = $model->about_us;
            $defaultTranslation->service = $model->service;
            $defaultTranslation->team_title = $model->team_title;
            $defaultTranslation->team_description = $model->team_description;
            $defaultTranslation->history = $model->history;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

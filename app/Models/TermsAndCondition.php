<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;

    public function getTranslatedTermsAndConditionAttribute()
    {
        if ($terms_and_condition = $this->translation?->terms_and_condition) {
            return $terms_and_condition;
        }
        return $this->terms_and_condition;
    }

    public function getTranslatedPrivacyPolicyAttribute()
    {
        if ($privacy_policy = $this->translation?->privacy_policy) {
            return $privacy_policy;
        }
        return $this->privacy_policy;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(TermsAndConditionTranslation::class, 'terms_and_condition_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(TermsAndConditionTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = TermsAndConditionTranslation::firstOrCreate([
                'language_code' => 'en',
                'terms_and_condition_id' => $model->id,
            ]);

            $defaultTranslation->terms_and_condition = $model->terms_and_condition;
            $defaultTranslation->privacy_policy = $model->privacy_policy;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = TermsAndConditionTranslation::firstOrCreate([
                'language_code' => 'en',
                'terms_and_condition_id' => $model->id,
            ]);

            $defaultTranslation->terms_and_condition = $model->terms_and_condition;
            $defaultTranslation->privacy_policy = $model->privacy_policy;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

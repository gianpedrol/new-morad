<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    public function getTranslatedQuestionAttribute()
    {
        if ($question = $this->translation?->question) {
            return $question;
        }
        return $this->question;
    }

    public function getTranslatedAnswerAttribute()
    {
        if ($answer = $this->translation?->answer) {
            return $answer;
        }
        return $this->answer;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(FaqTranslation::class, 'faq_id', 'id')->where('language_code', '=', $language);
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
                'faq_id' => $model->id,
            ]);

            $defaultTranslation->question = $model->question;
            $defaultTranslation->answer = $model->answer;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = FaqTranslation::firstOrCreate([
                'language_code' => 'en',
                'faq_id' => $model->id,
            ]);

            $defaultTranslation->question = $model->question;
            $defaultTranslation->answer = $model->answer;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }

}

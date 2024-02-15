<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
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

    public function getTranslatedCommentAttribute()
    {
        if ($comment = $this->translation?->comment) {
            return $comment;
        }
        return $this->comment;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(TestimonialTranslation::class, 'testimonial_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(TestimonialTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = TestimonialTranslation::firstOrCreate([
                'language_code' => 'en',
                'testimonial_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->designation = $model->designation;
            $defaultTranslation->comment = $model->comment;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = TestimonialTranslation::firstOrCreate([
                'language_code' => 'en',
                'testimonial_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->designation = $model->designation;
            $defaultTranslation->comment = $model->comment;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

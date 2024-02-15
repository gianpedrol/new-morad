<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    public function getTranslatedTopPropertyTitleAttribute()
    {
        if ($top_property_title = $this->translation?->top_property_title) {
            return $top_property_title;
        }
        return $this->top_property_title;
    }

    public function getTranslatedTopPropertyDescriptionAttribute()
    {
        if ($top_property_description = $this->translation?->top_property_description) {
            return $top_property_description;
        }
        return $this->top_property_description;
    }

    public function getTranslatedFeaturedPropertyTitleAttribute()
    {
        if ($featured_property_title = $this->translation?->featured_property_title) {
            return $featured_property_title;
        }
        return $this->featured_property_title;
    }

    public function getTranslatedFeaturedPropertyDescriptionAttribute()
    {
        if ($featured_property_description = $this->translation?->featured_property_description) {
            return $featured_property_description;
        }
        return $this->featured_property_description;
    }
    public function getTranslatedUrgentPropertyTitleAttribute()
    {
        if ($urgent_property_title = $this->translation?->urgent_property_title) {
            return $urgent_property_title;
        }
        return $this->urgent_property_title;
    }

    public function getTranslatedUrgentPropertyDescriptionAttribute()
    {
        if ($urgent_property_description = $this->translation?->urgent_property_description) {
            return $urgent_property_description;
        }
        return $this->urgent_property_description;
    }
    public function getTranslatedServiceTitleAttribute()
    {
        if ($service_title = $this->translation?->service_title) {
            return $service_title;
        }
        return $this->service_title;
    }

    public function getTranslatedServiceDescriptionAttribute()
    {
        if ($service_description = $this->translation?->service_description) {
            return $service_description;
        }
        return $this->service_description;
    }

    public function getTranslatedAgentTitleAttribute()
    {
        if ($agent_title = $this->translation?->agent_title) {
            return $agent_title;
        }
        return $this->agent_title;
    }

    public function getTranslatedAgentDescriptionAttribute()
    {
        if ($agent_description = $this->translation?->agent_description) {
            return $agent_description;
        }
        return $this->agent_description;
    }

    public function getTranslatedBlogTitleAttribute()
    {
        if ($blog_title = $this->translation?->blog_title) {
            return $blog_title;
        }
        return $this->blog_title;
    }

    public function getTranslatedBlogDescriptionAttribute()
    {
        if ($blog_description = $this->translation?->blog_description) {
            return $blog_description;
        }
        return $this->blog_description;
    }

    public function getTranslatedTestimonialTitleAttribute()
    {
        if ($testimonial_title = $this->translation?->testimonial_title) {
            return $testimonial_title;
        }
        return $this->testimonial_title;
    }

    public function getTranslatedTestimonialDescriptionAttribute()
    {
        if ($testimonial_description = $this->translation?->testimonial_description) {
            return $testimonial_description;
        }
        return $this->testimonial_description;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(HomePageTranslation::class, 'home_page_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(HomePageTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = HomePageTranslation::firstOrCreate([
                'language_code' => 'en',
                'home_page_id' => $model->id,
            ]);

            $defaultTranslation->top_property_title = $model->top_property_title;
            $defaultTranslation->top_property_description = $model->top_property_description;
            $defaultTranslation->featured_property_title = $model->featured_property_title;
            $defaultTranslation->featured_property_description = $model->featured_property_description;
            $defaultTranslation->urgent_property_title = $model->urgent_property_title;
            $defaultTranslation->urgent_property_description = $model->urgent_property_description;
            $defaultTranslation->service_title = $model->service_title;
            $defaultTranslation->service_description = $model->service_description;
            $defaultTranslation->agent_title = $model->agent_title;
            $defaultTranslation->agent_description = $model->agent_description;
            $defaultTranslation->blog_title = $model->blog_title;
            $defaultTranslation->blog_description = $model->blog_description;
            $defaultTranslation->testimonial_title = $model->testimonial_title;
            $defaultTranslation->testimonial_description = $model->testimonial_description;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = HomePageTranslation::firstOrCreate([
                'language_code' => 'en',
                'home_page_id' => $model->id,
            ]);

            $defaultTranslation->top_property_title = $model->top_property_title;
            $defaultTranslation->top_property_description = $model->top_property_description;
            $defaultTranslation->featured_property_title = $model->featured_property_title;
            $defaultTranslation->featured_property_description = $model->featured_property_description;
            $defaultTranslation->urgent_property_title = $model->urgent_property_title;
            $defaultTranslation->urgent_property_description = $model->urgent_property_description;
            $defaultTranslation->service_title = $model->service_title;
            $defaultTranslation->service_description = $model->service_description;
            $defaultTranslation->agent_title = $model->agent_title;
            $defaultTranslation->agent_description = $model->agent_description;
            $defaultTranslation->blog_title = $model->blog_title;
            $defaultTranslation->blog_description = $model->blog_description;
            $defaultTranslation->testimonial_title = $model->testimonial_title;
            $defaultTranslation->testimonial_description = $model->testimonial_description;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

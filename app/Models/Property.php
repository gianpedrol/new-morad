<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;


    protected $appends = ['averageRating'];

    public function getAverageRatingAttribute()
    {
        return $this->avgReview()->avg('avarage_rating') ? : '0';
    }

    public function avgReview(){
        return $this->hasMany(PropertyReview::class)->where('status', 1);
    }


    public function propertyType(){
        return $this->belongsTo(PropertyType::class);
    }

    public function propertyPurpose(){
        return $this->belongsTo(PropertyPurpose::class);
    }

    public function propertyAminities(){
        return $this->hasMany(PropertyAminity::class);
    }

    public function propertyImages(){
        return $this->hasMany(PropertyImage::class);
    }

    public function propertyNearestLocations(){
        return $this->hasMany(PropertyNearestLocation::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reviews(){
        return $this->hasMany(PropertyReview::class);
    }

    public function getTranslatedTitleAttribute()
    {
        if ($title = $this->translation?->title) {
            return $title;
        }
        return $this->title;
    }
    public function getTranslatedDescriptionAttribute()
    {
        if ($description = $this->translation?->description) {
            return $description;
        }
        return $this->description;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(PropertyTranslation::class, 'property_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(PropertyTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = PropertyTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = PropertyTranslation::firstOrCreate([
                'language_code' => 'en',
                'property_id' => $model->id,
            ]);

            $defaultTranslation->title = $model->title;
            $defaultTranslation->description = $model->description;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

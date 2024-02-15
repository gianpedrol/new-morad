<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function countryState(){
        return $this->belongsTo(CountryState::class);
    }

    public function addressCities(){
        return $this->hasMany(Address::class);
    }


    protected $fillable = [
        'name', 'slug','status'
    ];


    public function getTranslatedNameAttribute()
    {
        if ($name = $this->translation?->name) {
            return $name;
        }
        return $this->name;
    }

    public function translation($language = null)
    {
        if (!$language) {
            $language = getSessionLanguage();
        }

        return $this->hasOne(CityTranslation::class, 'city_id', 'id')->where('language_code', '=', $language);
    }

    public function translations()
    {
        return $this->hasMany(CityTranslation::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $defaultTranslation = CityTranslation::firstOrCreate([
                'language_code' => 'en',
                'city_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->save();
        });

        static::updated(function ($model) {
            $defaultTranslation = CityTranslation::firstOrCreate([
                'language_code' => 'en',
                'city_id' => $model->id,
            ]);

            $defaultTranslation->name = $model->name;
            $defaultTranslation->save();
        });

        static::deleted(function ($model) {
            if ($model->translations) {
                $model->translations()->delete();
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearestLocationTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nearest_location_id',
        'language_code',
        'location',
    ];
}

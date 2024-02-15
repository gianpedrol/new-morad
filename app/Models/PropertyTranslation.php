<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'language_code',
        'title',
        'description',
    ];
}

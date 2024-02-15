<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPurposeTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_purpose_id',
        'language_code',
        'custom_purpose',
    ];
}

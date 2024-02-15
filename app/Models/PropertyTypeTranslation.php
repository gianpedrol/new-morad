<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTypeTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_type_id',
        'language_code',
        'type',
    ];
}

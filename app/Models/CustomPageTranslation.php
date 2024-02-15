<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_page_id',
        'language_code',
        'page_name',
        'description',
    ];
}

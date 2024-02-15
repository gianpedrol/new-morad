<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AminityTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'aminity_id',
        'language_code',
        'aminity',
    ];
}

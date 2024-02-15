<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_id',
        'language_code',
        'address',
        'first_column',
        'second_column',
        'third_column',
        'copyright',
    ];
}

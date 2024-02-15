<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverviewTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'overview_id',
        'language_code',
        'name',
    ];
}

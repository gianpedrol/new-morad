<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['about_us_id', 'language_code', 'about_us', 'service', 'team_title', 'team_description', 'history'];
}

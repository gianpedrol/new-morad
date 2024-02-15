<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'language_code',
        'name',
        'designation',
    ];
}

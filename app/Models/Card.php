<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['uid'];

    const UPDATED_AT = null; // Match original schema
}

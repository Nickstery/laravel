<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}

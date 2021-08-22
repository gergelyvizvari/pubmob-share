<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $casts = [
        'content' => 'array',
    ];
}

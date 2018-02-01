<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopWord extends Model
{
    protected $table = 'stop_word';
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
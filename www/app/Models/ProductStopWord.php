<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStopWord extends Model
{
    protected $table = 'product_stop_word';
    protected $fillable = [
        'id','product_id','stop_word_id'
    ];

    public $timestamps = false;
}
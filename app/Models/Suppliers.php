<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $fillable = [
        'uuid',
        'code',
        'name',
        'slug',
        'email',
        'phone',
        'address',
    ];

    public static function booted(){
        static::creating(function($model){
            $model->uuid = (string) Str::uuid();
        });
    }
}

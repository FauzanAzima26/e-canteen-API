<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'uuid',
        'category_id',
        'supplier_id',
        'name',
        'slug',
        'image',
        'price',
        'stock',
        'description'
    ];

    public static function booted(){
        static::creating(function($model){
            $model->uuid = (string) Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }
}

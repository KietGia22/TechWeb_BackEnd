<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Product_Category extends Pivot
{
    //
    protected $table = 'product_category';

    protected $timestamp = false;

    public $timestamps = false;

     public $incrementing = false;

    protected $fillable = [
        'product_id',
        'category_id',
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
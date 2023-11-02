<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Product_Category extends Pivot
{
    //
    protected $table = 'product_category';
    protected $hidden = [
        'product_id',
        'category_id',
    ];
}
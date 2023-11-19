<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Product_Category extends Pivot
{
    //
    protected $table = 'product_category';

    protected $timestamp = false;

    public $timestamps = false;

    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'category_id',
        'new_category_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
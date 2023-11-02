<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    public $timestamps = false;

     public $incrementing = false;

    protected $fillable = [
        'product_id',
        'name_pr',
        'name_serial',
        'detail',
        'price',
        'quantity_pr',
        'guarantee period'
    ];

    protected $hidden = [
        'img_id',
        'supplier_id',
        'updated_at',
    ];
}

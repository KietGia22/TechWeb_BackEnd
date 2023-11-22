<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailOrder extends Model
{
    use HasFactory;

    protected $table = "detail_order";

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = ['order_id', 'product_id'];

    protected $fillable = [
        'order_id',
        'product_id',
        'price_pr',
        'quantity_pr',
    ];

    protected $hidden = [
        'updated_at',
        'order_id'
    ];

    public function product_id():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

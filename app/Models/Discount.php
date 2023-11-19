<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $table = "discounts";

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'discount_id',
        'discount_code',
        'discount_amount',
        'discount_date',
        'discount_date_to'
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function order():HasMany
    {
        return $this->hasMany(Order::class, 'discount', 'discount_id');
    }
}
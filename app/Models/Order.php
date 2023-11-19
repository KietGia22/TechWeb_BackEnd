<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = "order";

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_id',
        'user_id',
        'create_order_at',
        'name',
        'email',
        'phone',
        'address',
        'state',
        'note',
        'total',
        'discount',
        'delivery_fee'
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function CustumerInfor():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail_order():HasMany
    {
        return $this->hasMany(DetailOrder::class, 'order_id', 'order_id');
    }

    public function discountInFor():BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount');
    }
}
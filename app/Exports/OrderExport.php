<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }

    public function headings():array
    {
        return ['order_id', 'user_id', 'create_order_at', 'name', 'email', 'phone', 'address', 'state', 'note', 'total', 'discount', 'delivery_fee'];
    }
}

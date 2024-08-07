<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // fillable all fileds
    protected $fillable = ['shop_id', 'user_id', 'order_id', 'name', 'order_number', 'data', 'line_items', 'order_status', 'protection_status', 'total_price', 'protection_price', 'subtotal_price', 'total_tax', 'total_discounts', 'admin_graphql_api_id'];

    // cast data and line_items field to json
    protected $casts = [
        'data' => 'json',
        'line_items' => 'json'
    ];


    //belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

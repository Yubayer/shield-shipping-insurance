<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'user_id', 'product_id', 'product_handle', 'data', 'status', 'admin_graphql_api_id'];

    protected $casts = [
        'data' => 'json'
    ];
}

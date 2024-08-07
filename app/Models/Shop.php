<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id', 'domain', 'data', 'status', 'primary_location_id','admin_graphql_api_id','app_url'];

    protected $casts = [
        'data' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function widget()
    {
        return $this->hasOne(Widget::class);
    }
}

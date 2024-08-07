<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;

class User extends Authenticatable implements IShopModel
{
    use HasApiTokens, HasFactory, Notifiable;
    use ShopModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_id',
        'shop_gid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //hasOne Shop
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    //hasOne Settings
    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    //hasOne Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //hasMany Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //hasOne Metafield
    public function metafield()
    {
        return $this->hasOne(Metafield::class);
    }

    //hasOne Widget
    public function widget()
    {
        return $this->hasOne(Widget::class);
    }
}

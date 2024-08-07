<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'shop_id',
        'user_id',
        'styles',
        'settings',
        'scripts',
    ];

    protected $casts = [
        'styles' => 'array',
        'settings' => 'array',
        'scripts' => 'array',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

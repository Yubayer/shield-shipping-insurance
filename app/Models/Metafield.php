<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metafield extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
        'product_metafield',
        'rules_metafield',
    ];

    //cast json
    protected $casts = [
        'product_metafield' => 'json',
        'rules_metafield' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

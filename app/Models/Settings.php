<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    // fullable all column in settings table, product id, product handle, shop id, rules, status
    protected $fillable = ['shop_id', 'rules'];

    //type cast rules as json
    protected $casts = [
        'rules' => 'json'
    ];

    //belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

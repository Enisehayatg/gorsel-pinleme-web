<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'book_id', 'quantity', 'total_price', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

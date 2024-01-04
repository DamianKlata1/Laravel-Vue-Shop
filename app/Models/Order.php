<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['total_price', 'user_address_id', 'address_id', 'status', 'session_id', 'created_by', 'updated_by'];

    function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

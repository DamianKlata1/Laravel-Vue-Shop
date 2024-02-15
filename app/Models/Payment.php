<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = ['order_id', 'type', 'amount', 'status', 'created_by', 'updated_by'];
    use HasFactory;

    public function order(): HasOne
    {
        return $this->hasOne(Order::class,'id','order_id');
    }
}

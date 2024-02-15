<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['total_price', 'user_address_id', 'address_id', 'status', 'session_id', 'created_by', 'updated_by'];

    function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    function scopeFiltered(Builder $query): void
    {
        $query
            ->when(request('search'), function (Builder $q) {
                $q->whereHas('orderItems.product', function (Builder $q) {
                    $q->where('title', 'like', '%' . request('search') . '%');
                })->orWhereHas('created_by', function (Builder $q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                })->orWhere('id', 'like', request('search'));
                ;
            });
    }
}

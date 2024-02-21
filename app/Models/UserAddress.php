<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'address',
        'isMain'
    ];
    use HasFactory;

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

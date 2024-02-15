<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Review extends Model
{
    use HasFactory;
    protected $fillable=['user_id','product_id','rating','comment','helpful_users'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function helpfulUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'review_helpfuls')->withTimestamps();
    }

//    TODO: Filter reviews
//
//    public function scopeFiltered(Builder $query){
//
//    }
}

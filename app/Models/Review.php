<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable=['user_id','product_id','rating','comment','helpful_users'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function helpfulUsers(){
        return $this->belongsToMany(User::class,'review_helpfuls')->withTimestamps();
    }

    public function scopeFiltered(Builder $query){

    }
}

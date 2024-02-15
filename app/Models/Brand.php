<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;


    protected $fillable = [
        'name',
        'slug'
    ];
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public static function getBrandProductCounts(): Collection
    {
        return Brand::withCount([ 'products' => function ($query) {
            $query->where('published', true);
        }])->pluck('products_count', 'id');

    }
}

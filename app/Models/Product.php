<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'quantity',
        'description',
        'published',
        'inStock',
        'price',
        'created_by',
        'updated_by',
        'brand_id',
        'category_id',
        'deleted_by',
        'wishlistItems',
        'reviews'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function product_images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function calculateRating(): float
    {
        return $this->reviews()->avg('rating') ? $this->reviews()->avg('rating') : 0;
    }

    public function scopeFiltered(Builder $query): void
    {
        $query
            ->when(request('brands'), function (Builder $q) {
                $q->whereIn('brand_id', request('brands'));
            })
            ->when(request('categories'), function (Builder $q) {
                $q->whereIn('category_id', request('categories'));
            })
            ->when(request('prices'), function (Builder $q) {
                $q->whereBetween('price', [
                    request('prices.from', 0),
                    request('prices.to', 1000000)
                ]);
            })
            ->when(request('search'), function (Builder $q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                    ->orWhereHas('brand', function (Builder $q) {
                        $q->where('name', 'like', '%' . request('search') . '%');
                    })
                    ->orWhereHas('category', function (Builder $q) {
                        $q->where('name', 'like', '%' . request('search') . '%');
                    });
            })
            ->when(request('sort'), function (Builder $q) {
                $sort = request('sort');
                if ($sort == 'price-low-to-high') {
                    $q->orderBy('price', request('order', 'asc'));
                } elseif ($sort == 'price-high-to-low') {
                    $q->orderBy('price', request('order', 'desc'));
                } elseif ($sort == 'newest') {
                    $q->orderBy('created_at', request('order', 'desc'));
                } elseif ($sort == 'most-popular') {
                    $q->withCount('wishlistItems')->orderBy('wishlist_items_count', request('order', 'desc'));
                }
            });;
    }


}

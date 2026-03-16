<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'slug',
        'actual_price',
        'discounted_price',
        'discount_percentage',
        'small_stock',
        'medium_stock',
        'large_stock',
        'xl_stock',
        'bottom_style',
        'color_type',
        'product_code',
        'lining_attached',
        'number_of_pieces',
        'product_type',
        'season',
        'shirt_fabric',
        'top_style',
        'trouser_fabrics',
    ];

    protected $casts = [
        'actual_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function firstMedia()
    {
        return $this->hasOne(ProductMedia::class)->oldestOfMany('sort_order');
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->small_stock
            + (int) $this->medium_stock
            + (int) $this->large_stock
            + (int) $this->xl_stock;
    }
}
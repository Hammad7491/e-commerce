<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    protected $table = 'product_media';

    protected $fillable = [
        'product_id',
        'file_path',
        'file_type',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
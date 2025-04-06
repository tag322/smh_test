<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\ProductTag;
use App\Models\Brand;
use App\Models\ProductReview;
use App\Models\ProductImage;

class Phone extends Model
{
    protected $table = 'phones';

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'price',
        'discountPercentage',
        'rating',
        'stock',
        'brand_id',
        'sku',
        'weight',
        'dimensions',
        'warrantyInformation',
        'shippingInformation',
        'availabilityStatus',
        'returnPolicy',
        'minimumOrderQuantity',
        'meta',
        'thumbnail'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags() {
        return $this->belongsToMany(ProductTag::class, 'product_tags_pivot', 'product_id', 'tag_id');
    }

    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function reviews() {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}

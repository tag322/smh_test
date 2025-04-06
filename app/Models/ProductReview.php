<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = [
        'rating',
        'comment',
        'reviewerId',
        'product_id',
    ];
}

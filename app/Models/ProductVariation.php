<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price',
        'length',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function technicalSpecifications()
    {
        return $this->hasMany(TechnicalSpecification::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

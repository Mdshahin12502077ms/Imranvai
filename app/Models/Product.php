<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
      'name',
      'description',
      'status',
    ];

    public function images()
    {
      return $this->hasMany(ProductGallery::class);
    }

    public function variations()
    {
      return $this->hasMany(ProductVariation::class);
    }

    public function technicalSpecifications()
    {
        return $this->hasMany(TechnicalSpecification::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderedQuantities()
    {
        return $this->hasMany(Order::class)
                    ->where('order_status', '!=', 'cancelled')
                    ->withSum('productVariation', 'stock');
    }
}

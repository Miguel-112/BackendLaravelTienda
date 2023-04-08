<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotorcyclePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'provider_id',
        'brand_id',
        'purchase_price',
        'sale_price',
        'quantity',
        'image',
        'iva'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function brand()
    {
        return $this->belongsTo(Marca::class);
    }
}

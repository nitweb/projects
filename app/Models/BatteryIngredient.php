<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryIngredient extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categoryForBattery()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function productForBattery()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

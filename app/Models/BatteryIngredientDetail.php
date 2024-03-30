<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryIngredientDetail extends Model
{
    use HasFactory;

    public function ingredient(){
        return $this->belongsTo(Ingredient::class, 'ingredient_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMeta extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}

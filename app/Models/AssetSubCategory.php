<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetSubCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function AssetCategory()
    {
        return $this->belongsTo(AssetCategory::class, 'cat_id', 'id');
    }
}

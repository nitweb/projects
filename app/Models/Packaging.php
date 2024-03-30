<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function packageMeta()
    {
        return $this->hasMany(PackagingMeta::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}

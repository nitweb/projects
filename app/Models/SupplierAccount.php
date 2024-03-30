<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccount extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Packaging::class, 'packet_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_name', 'id');
    }
}

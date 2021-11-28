<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    public function amount()
    {
        return $this->products->count();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('id');
    }
}
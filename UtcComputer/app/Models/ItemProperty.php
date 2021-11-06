<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProperty extends Model
{
    protected $table = 'item_property';

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function AssetProperty()
    {
        return $this->belongsTo(AssetProperty::class);
    }
}
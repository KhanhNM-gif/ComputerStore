<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;
    protected $table = 'manufacturer';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function GetList()
    {
        return Manufacturer::all();
    }
}
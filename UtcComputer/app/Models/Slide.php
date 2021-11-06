<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slide';

    public static function GetList()
    {
        return Slide::select('id', 'slide_image', 'side_title', 'item_id')
            ->where('start_date', '<=', date("Y-m-d H:i:s"))
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', ">=", date("Y-m-d H:i:s"));
            })
            ->take(5)
            ->get();
    }
}
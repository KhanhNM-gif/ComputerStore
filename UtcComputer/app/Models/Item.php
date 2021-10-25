<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';

    protected $fillable = [
        'id',
        'asset_id',
        'item_name',
        'manufacturer_id',
        'price',
        'quanlity',
        'created_at',
        'updated_at'
    ];

    public static function GetListSearch($textSearch, $pageSize, $ltAssetID)
    {
        return Item::whereIn('asset_ID', array_column($ltAssetID, 'asset_id'))->paginate($pageSize);
    }
}
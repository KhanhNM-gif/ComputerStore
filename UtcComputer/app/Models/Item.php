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

    public static function GetListSearch($search, $ltAssetID)
    {
        $item = Item::query();

        if (array_key_exists('textSearch', $search)) {
            $item->where('item_name', 'LIKE', '%' . $search['textSearch'] . '%');
        }

        if (array_key_exists('statusId', $search)) {
            $item->where('status_id', $search['textSearch']);
        }

        if (array_key_exists('maxPrice', $search)) {
            $item->where('promotional_price', '<=', $search['maxPrice']);
        }

        if (array_key_exists('minPrice', $search)) {
            $item->where('promotional_price', '>=', $search['minPrice']);
        }

        if ($search['assetID'] != 0) {
            $item->whereIn('asset_ID', array_column($ltAssetID, 'id'));
        }

        if (array_key_exists('OrderBy', $search)) {
            switch ($search['OrderBy']) {
                case 1:
                    $item->orderBy('created_at', 'DESC');
                    break;
                case 2:
                    $item->orderBy('num_review', 'DESC');
                    break;
                case 3:
                    $item->orderBy('price - promotional_price', 'DESC');
                    break;
                case 4:
                    $item->orderBy('promotional_price', 'ASC');
                    break;
                case 5:
                    $item->orderBy('promotional_price', 'DESC');
                    break;
                default:
                    break;
            }
        }

        return $item->paginate($search['pageSize']);
    }

    public static function GetOneView($itemID)
    {
        return Item::selectRaw('item.*,item_status.status_name,manufacturer.name')
            ->where('item.id', $itemID)
            ->join('item_status', 'item_status.id', '=', 'item.status_id')
            ->join('manufacturer', 'manufacturer.id', '=', 'item.manufacturer_id')
            ->first();
    }

    public static function GetOne($itemID)
    {
        return Item::where('id', $itemID)->first();
    }
}
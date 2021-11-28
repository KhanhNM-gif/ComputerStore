<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use HasFactory;

    protected $table = 'item';

    public $itemProperty;

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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static function GetListSearch($search, $ltAssetID)
    {
        $query_item = Item::query();

        $query_item->select('id', 'item_name', 'price', 'image', 'promotional_price', 'status_id', 'status_name');

        if (array_key_exists('textSearch', $search)) {
            $query_item->where('item_name', 'LIKE', '%' . $search['textSearch'] . '%');
        }

        if (array_key_exists('statusId', $search)) {
            $query_item->where('status_id', $search['statusId']);
        }

        if (array_key_exists('maxPrice', $search)) {
            $query_item->where('promotional_price', '<=', $search['maxPrice']);
        }

        if (array_key_exists('minPrice', $search)) {
            $query_item->where('promotional_price', '>=', $search['minPrice']);
        }

        if ($search['assetID'] != 0) {
            $query_item->whereIn('asset_ID', array_column($ltAssetID, 'id'));
        }

        if (array_key_exists('manufacturerIds', $search)) {
            $query_item->whereIn('manufacturer_id', array_map('intval', explode(',', $search['manufacturerIds'])));
        }

        if (array_key_exists('isNew', $search) && $search['isNew']) {
            $query_item->whereRaw("DATEDIFF(item.created_at,now()) < 30");
        }

        if (array_key_exists('isDiscount', $search) && $search['isDiscount']) {
            $query_item->whereRaw("price - promotional_price > 0");
        }

        if (array_key_exists('OrderBy', $search)) {
            switch ($search['OrderBy']) {
                case 1:
                    $query_item->orderBy('created_at', 'DESC');
                    break;
                case 2:
                    $query_item->orderBy('num_review', 'DESC');
                    break;
                case 3:
                    $query_item->orderByRaw('price - promotional_price DESC');
                    break;
                case 4:
                    $query_item->orderBy('promotional_price', 'ASC');
                    break;
                case 5:
                    $query_item->orderBy('promotional_price', 'DESC');
                    break;
                default:
                    break;
            }
        }
        
        $item = $query_item->join('item_status', 'item_status.id', '=', 'item.status_id')->paginate($search['pageSize']);

        return $item;
    }



    public static function GetOneView($itemID)
    {
        $item = Item::selectRaw('item.*,item_status.status_name,manufacturer.name')
            ->where('item.id', $itemID)
            ->join('item_status', 'item_status.id', '=', 'item.status_id')
            ->join('manufacturer', 'manufacturer.id', '=', 'item.manufacturer_id')
            ->first();

        $item->attributes['itemProperties'] = $item->getPropertyByID($itemID);

        return $item;
    }
    private function getPropertyByID($itemID)
    {
        return $this->itemProperty = $this::find($itemID)->itemProperties;
    }

    public static function GetOne($itemID)
    {
        return Item::where('id', $itemID)->first();
    }

    public function itemProperties()
    {
        return $this->hasMany(ItemProperty::class);
    }

    public static function GetListDiscount()
    {
        return Item::select('id', 'item_name', 'price', 'image', 'promotional_price')
            ->orderByRaw('price - promotional_price DESC')
            ->take(10)
            ->get();
    }

    public static function GetListNew()
    {
        return Item::select('id', 'item_name', 'price', 'image', 'promotional_price')
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get();
    }
}
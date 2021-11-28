<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetProperty extends Model
{
    use HasFactory;
    protected $table = 'asset_property';

    protected $fillable = [
        'id',
        'asset_id',
        'asset_property_name',
        'type_property_asset_ID'
    ];

    public static function get_list_by_assetID($assetID)
    {
        return AssetProperty::where(['asset_ID' =>   $assetID, 'is_active' => true, 'is_delete' => false])->toArray();
    }
}

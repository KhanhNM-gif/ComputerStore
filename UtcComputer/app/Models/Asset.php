<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'asset';

    protected $fillable = [
        'id',
        'asset_name',
        'asset_parent_id',
        'is_active',
        'is_delete',
        'created_at',
        'updated_at',
    ];

    public static function GetListByParentID($assetParentID)
    {
        return Asset::where(['asset_parent_id' => $assetParentID, 'is_active' => true, 'is_delete' => false])->orderBy('order_by', 'desc')->get();
    }

    public static function GetOneByID($assetID, &$output)
    {
        $output = Asset::where(['id' => $assetID])->first();

        if (!$output) return "Loại sản phẩm không tồn tại";
        if (!$output['is_active']) return "Loại sản phẩm chưa được kích hoạt";
        if ($output['is_delete']) return "Loại sản phẩm đã bị xóa";

        return '';
    }

    public static function GetListChildByAssetID($assetID)
    {
        return DB::select('call usp_Asset_GetAllChild(?)', [$assetID]);
    }
}
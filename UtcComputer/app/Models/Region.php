<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use League\Flysystem\Config;

class Region extends Model
{
    use HasFactory;

    protected $table = 'region';

    protected $fillable = [
        'id',
        'parent_id',
        'region_name',
        'region_name_not_mark',
        'region_level',
        'is_active',
        'order_by'
    ];

    protected $hidden = [];

    public static function ValidateAddress($wardID, $districtID, $provinceID)
    {
        Region::GetOneByRegionID($wardID, $ward);
        if ($ward) {
            if ($ward['parent_id'] == $districtID) {
                Region::GetOneByRegionID($districtID, $district);
                if ($district) {
                    if ($district['parent_id'] == $provinceID) {
                        Region::GetOneByRegionID($districtID, $province);
                        if (!$province) return "Tỉnh chọn không tồn tại";
                    } else {
                        return 'Quận\Huyện đã chọn không nằm trong Tỉnh đã chọn';
                    }
                } else {
                    return 'Quận\Huyện chọn không tồn tại';
                }
            } else {
                return 'Phường/Xã đã chọn không trogn tỉnh quận huyện';
            }
        } else {
            return 'Phường/Xã chọn không tồn tại';
        }

        return null;
    }
    public static function GetOneByRegionID($regionID, &$outRegion)
    {
        $region_json = Redis::hGet(config('constants.hash_redis.region'), $regionID);
        if ($region_json) {
            $outRegion = new Region(
                json_decode($region_json, true)
            );
            return;
        } else {
            $outRegion = Region::where('id', $regionID)->first();
            Redis::hSet(config('constants.hash_redis.region'), $regionID, json_encode($outRegion));
            return;
        }
    }
    public static function GetListByParentID($parentID, &$outListRegion)
    {
        $outListRegion_json = Redis::hGet(config('constants.hash_redis.tree_region'), $parentID);
        if ($outListRegion_json) {
            $object = (array) json_decode($outListRegion_json, true);
            $outListRegion = Region::hydrate($object);
            return;
        } else {
            $outListRegion = Region::where('parent_id', $parentID)->get();
            Redis::hSet(config('constants.hash_redis.tree_region'), $parentID, json_encode($outListRegion));
            return;
        }
    }
}
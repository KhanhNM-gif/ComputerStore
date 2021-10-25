<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Item;
use App\Traits\RemoveMark;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use RemoveMark;

    public function GetListPaging(Request $request)
    {
        $msgError = $this->GetListValidate($request, $output);
        if ($msgError) {
            return response()->json([
                'message' => $msgError,
                "errors" => []
            ], 400);
        }

        $dt = $output['dt'];

        $textSearch = "";
        if (isset($dt->textSearch)) $textSearch = $this->RemoveMarkVietNamese($dt['textSearch']);

        $ltItem = Item::GetListSearch($textSearch, $dt['pageSize'], $output['ltChildAsset']);

        return Response(['ltItem' => $ltItem], 200);
    }

    private function GetListValidate($request, &$output)
    {
        $dataRequest = $request->validate([
            'textSearch' => 'string|max:255|nullable',
            'assetID' => 'numeric|integer',
            'pageSize' => 'numeric|integer',
        ]);

        if ($dataRequest['assetID'] != 0) {
            $msgError = Asset::GetOneByID($dataRequest['assetID'], $outAsset);
            if ($msgError) return $msgError;

            $ltChildAsset = Asset::GetListChildByAssetID($dataRequest['assetID']);
        }

        $output = [
            'dt' => $dataRequest,
            'ltChildAsset' => $ltChildAsset
        ];

        return null;
    }
}
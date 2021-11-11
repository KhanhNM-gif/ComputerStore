<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Item;
use App\Traits\RemoveMark;
use Illuminate\Http\Request;
use App\Rules\ListId;
use PhpParser\Node\Expr\List_;

class ItemController extends Controller
{
    use RemoveMark;

    public function GetListSearch(Request $request)
    {
        $msgError = $this->GetListValidate($request, $output);
        if ($msgError) {
            return response()->json([
                'message' => $msgError,
                "errors" => []
            ], 400);
        }
        $dt = $output['dt'];

        if (isset($dt->textSearch)) $dt['textSearch'] = $this->RemoveMarkVietNamese($dt['textSearch']);

        $ltItem = Item::GetListSearch($dt, $output['ltChildAsset']);

        return Response(['ltItem' => $ltItem], 200);
    }
    private function GetListValidate($request, &$output)
    {
        $dataRequest = $request->validate([
            'textSearch' => 'string|max:255|nullable',
            'statusId' => 'numeric|integer|nullable',
            'manufacturerIds' => ['string', 'max:255', new ListId(), 'nullable'],
            'minPrice' => 'regex:/^\d*$/|nullable',
            'maxPrice' => 'regex:/^\d*$/|nullable',
            'orderBy' => 'numeric|integer|nullable',
            'assetID' => 'numeric|integer|required',
            'pageSize' => 'numeric|integer|required',
            'page' => 'numeric|integer|required',
            'isNew' => 'boolean|nullable',
            'isDiscount' => 'boolean|nullable',
        ]);

        $ltChildAsset = null;
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

    public function GetOne(Request $request)
    {
        $dataRequest = $request->validate([
            'itemID' => 'numeric|integer|required'
        ]);

        $item = Item::GetOneView($dataRequest['itemID']);

        return Response(['item' => $item], 200);
    }

    public function GetListDiscount()
    {
        $ltItem = Item::GetListDiscount();

        return Response(['ltItem' => $ltItem], 200);
    }

    public function GetListNew()
    {
        $ltItem = Item::GetListNew();

        return Response(['ltItem' => $ltItem], 200);
    }
}
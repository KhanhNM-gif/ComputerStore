<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function GetListBy(Request $request)
    {
        $data = $request->validate([
            'parentID' => 'numeric|integer',
        ]);

        $msgError = Region::GetListByParentID($data['parentID'], $outListRegion);
        if ($msgError) return response()->json([
            'message' => $msgError,
            "errors" => []
        ], 400);

        return Response([
            'List' => $outListRegion,
        ], 200);
    }
}
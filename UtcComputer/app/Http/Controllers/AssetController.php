<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function GetList(Request $request)
    {
        $msg_error = $this->GetListValidate($request, $output);
        if ($msg_error) {
            return response()->json([
                'message' => $msg_error,
                "errors" => []
            ], 400);
        }

        $ltAsset = Asset::GetListByParentID($output['id']);

        return Response(['ltAsset' => $ltAsset], 200);
    }
    private function GetListValidate($request, &$output)
    {
        $data = $request->validate([
            'parentID' => 'numeric|integer',
        ]);

        if ($data['parentID'] != 0) {
            $msgError = Asset::GetOneByID($data['parentID'], $output);
            if ($msgError) return $msgError;
        }

        return null;
    }
}
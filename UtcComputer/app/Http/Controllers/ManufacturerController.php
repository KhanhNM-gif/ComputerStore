<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use Facade\FlareClient\Http\Response;

class ManufacturerController extends Controller
{
    public function GetList(Request $request)
    {
        $dataRequest = $request->validate([
            'assetID' => 'numeric|integer|required'
        ]);

        $ltManufacturer = Manufacturer::GetList($dataRequest['assetID']);

        return Response(['ltManufacturer' => $ltManufacturer], 200);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manufacturer;
use Facade\FlareClient\Http\Response;

class ManufacturerController extends Controller
{
    public function GetList(Request $request)
    {
        $ltManufacturer = Manufacturer::GetList();

        return Response(['ltManufacturer' => $ltManufacturer], 200);
    }
}

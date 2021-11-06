<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class SlideController extends Controller
{
    public function GetList()
    {
        $ltSlide = Slide::GetList();

        return Response(['ltSlide' => $ltSlide], 200);
    }
}
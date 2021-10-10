<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabController extends Controller
{
    public function store(Request $request)
    {
        return Response($request,200);
    }
    
}

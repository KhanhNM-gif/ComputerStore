<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartManager;
use App\Models\Item;

class CartController extends Controller
{
    public function AddToCart(Request $request)
    {
        $dataRequest = $request->validate([
            'itemId' => 'numeric|integer|required',
            'quanlity' => 'numeric|integer|required'
        ]);

        if ($dataRequest['quanlity'] <= 0) {
            return response()->json([
                'message' => 'Số lượng phải lớn hơn 0',
                "errors" => []
            ], 400);
        }

        $item = Item::GetOne($dataRequest['itemId']);
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại',
                "errors" => []
            ], 400);
        }
        $cart = new CartManager();
        $cart->AddToCart($dataRequest['itemId'], $dataRequest['quanlity']);

        return Response(['ltItem' => $cart->GetCart()], 200);
    }

    public function GetCart()
    {
        $cart = new CartManager();
        return Response(['ltItem' => $cart->getListItemInCart()], 200);
    }

    public function DeleteItemCart(Request $request)
    {
        $dataRequest = $request->validate([
            'itemID' => 'numeric|integer|required',
        ]);

        $item = Item::GetOne($dataRequest['itemID']);
        if (!$item) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại',
                "errors" => []
            ], 400);
        }

        $cart = new CartManager();
        $cart->deleteProduct($dataRequest['itemID']);

        return Response(['ltItem' => $cart->GetCart()], 200);
    }
}
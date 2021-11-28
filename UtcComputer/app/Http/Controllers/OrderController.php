<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\CartManager;
use App\Models\Order;
use App\Rules\PhoneNumber;

class OrderController extends Controller
{
    public function CreateOrder(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:accounts,email|email|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
            'fullname' => 'required|string|max:255',
            'phone_number' => ['required', 'string', new PhoneNumber],
            'address' => 'required|string|max:255',
            'province_id' => 'required|numeric|integer',
            'district_id' => 'required|numeric|integer',
            'ward_id' => 'required|numeric|integer'
        ]);

        $msgError = Region::ValidateAddress($fields['ward_id'], $fields['district_id'], $fields['province_id']);
        if ($msgError) {
            return response()->json([
                'message' => $msgError,
                "errors" => []
            ], 400);
        }

        $cart = new CartManager();

        $oder = Order::create([
            'shopping_cart_id' => $cart->getCart()['ID'],
            'email' => $fields['email'],
            'name' => $fields['name'],
            'password' => password_hash($fields['password'], PASSWORD_DEFAULT),
            'fullname' => $fields['fullname'],
            'phone_number' => $fields['phone_number'],
            'address' => $fields['address'],
            'province_id' => $fields['province_id'],
            'district_id' => $fields['district_id'],
            'ward_id' => $fields['ward_id']
        ]);

        return Response([
            'oder' => $oder,
        ], 201);
    }
}
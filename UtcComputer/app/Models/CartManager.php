<?php

namespace App\Models;

use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Redis;

class CartManager
{
    private $cart;

    public function __construct()
    {
        $this->cart = $this->findOrCreate($this->findCache());
    }

    public function getListItemInCart()
    {
        return $this->cart->items()->withPivot('quantity')->get();
    }

    public function getCart()
    {
        return $this->cart->get();
    }

    public function addToCart($id, $quantity)
    {
        $this->cart->items()->sync([$id => ['quantity' => $quantity]], false);
    }

    public function deleteProduct($itemID)
    {
        return $this->cart->items()->wherePivot('id', $itemID)->detach();
    }

    public function getAmount()
    {
        return $this->cart->items()->sum('price');
    }
    private function findOrCreate($cartId = null)
    {
        $cart = null;
        if (is_null($cartId)) {
            $cart = ShoppingCart::create();
        } else {
            $cart = ShoppingCart::find($cartId);
            if (is_null($cart)) {
                $cart = ShoppingCart::create();
            }
        }
        Redis::hSet(config('constants.hash_redis.cart_id'), auth()->user()->id, $cart->id);
        return $cart;
    }

    private function findCache()
    {
        return Redis::hGet(config('constants.hash_redis.cart_id'), auth()->user()->id);
    }
}
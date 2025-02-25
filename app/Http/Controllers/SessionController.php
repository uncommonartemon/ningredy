<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product;

class SessionController extends Controller
{
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session('cart', []);
        $found = false;

        foreach ($cart as &$item) {
            if ($item['product']['id'] === $product->id) {
                $item['qty'] += 1;  
                $found = true;
                break; 
            }
        }
        if (!$found) {
            $cart[] = [
                'product' => $product,  
                'qty' => 1,           
            ];
        }
        session(['cart' => $cart]);
    }


    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        foreach ($cart as $key => $item) {
            if ($item['product']->id == $id) {
                unset($cart[$key]);
                session(['cart' => array_values($cart)]);
                break; 
            }
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
    }

    public function addQty($id) {
        $cart = session('cart', []);
        foreach ($cart as $key => $item) {
            if ((int)$item['product']['id'] == (int)$id) {
                $cart[$key]['qty'] = $item['qty'] + 1;
                session(['cart' => $cart]);
                break;
            }
        }
    }
    public function removeQty($id) {
        $cart = session('cart', []);
        foreach ($cart as $key => $item) {
            if ((int)$item['product']['id'] == (int)$id && $item['qty'] > 1) {
                $cart[$key]['qty'] = $item['qty'] - 1;
                session(['cart' => $cart]);
                //return response()->json($cart);
            }
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        return view('frontend.home');
    }

    public function products()
    {
        return view('frontend.products');
    }

    public function productDetail($id)
    {
        return view('frontend.product-detail');
    }

    public function cart()
    {
        return view('frontend.cart');
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }
}
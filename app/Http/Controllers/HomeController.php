<?php

namespace App\Http\Controllers;

use App\MarketProduct;
use App\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    public function fixFotos()
    {
        $mProducts = MarketProduct::where('parent', '<>', 'null')->get();
        foreach ($mProducts as $mProduct) {
            $product = $mProduct->getProduct;
            $product->img_product = $mProduct->getParent->img_product;
            $product->update();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function checkout($id)
    {
        $product = Product::findOrFail($id);
        $razorpayKey = env('RAZORPAY_KEY');

        return view('checkout', compact('product', 'razorpayKey'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MotorcyclePart;

class ProductController extends Controller
{
   
    public function index()
    {
        $product = Product::all();
        return $product;
    }

    
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        
    }

    
    public function show(Product $product)
    {
        
    }

   
    public function edit(Product $product)
    {
        
    }

  
    public function update(Request $request)
    {

        return var_dump($request->all());
        
    }

   
    public function destroy(Product $product)
    {
        
    }
}
